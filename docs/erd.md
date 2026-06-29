# ERD

## Entitas Utama

| Entitas               | Deskripsi                                                                       |
| --------------------- | ------------------------------------------------------------------------------- |
| `users`               | Menyimpan data akun admin dan kasir                                             |
| `categories`          | Menyimpan kategori produk/menu                                                  |
| `products`            | Menyimpan data produk, harga, stok, status aktif, dan gambar                    |
| `transactions`        | Menyimpan header transaksi, invoice, customer, pembayaran, dan status transaksi |
| `transaction_details` | Menyimpan detail produk yang dibeli dalam transaksi                             |

## Relasi Utama

| Relasi                          | Keterangan                                          |
| ------------------------------- | --------------------------------------------------- |
| User - Transaction              | Satu user/kasir dapat menangani banyak transaksi    |
| Category - Product              | Satu kategori memiliki banyak produk                |
| Transaction - TransactionDetail | Satu transaksi memiliki banyak detail transaksi     |
| Product - TransactionDetail     | Satu produk dapat muncul di banyak detail transaksi |

## ERD Sederhana

```mermaid
erDiagram
    USERS ||--o{ TRANSACTIONS : handles
    CATEGORIES ||--o{ PRODUCTS : contains
    TRANSACTIONS ||--o{ TRANSACTION_DETAILS : has
    PRODUCTS ||--o{ TRANSACTION_DETAILS : included_in

    USERS {
        bigint id
        string name
        string username
        string password
        string role
    }

    CATEGORIES {
        bigint id
        string name
    }

    PRODUCTS {
        bigint id
        bigint category_id
        string name
        decimal price
        int stock
        boolean is_active
    }

    TRANSACTIONS {
        bigint id
        bigint user_id
        string invoice_number
        string customer_name
        string order_type
        string payment_method
        string payment_status
        decimal subtotal
        decimal discount
        decimal tax
        decimal total
        decimal paid
        decimal change
    }

    TRANSACTION_DETAILS {
        bigint id
        bigint transaction_id
        bigint product_id
        int quantity
        decimal price
        decimal subtotal
    }
```
