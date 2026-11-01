-- Jalankan di phpMyAdmin pada database tahunutrifeed.
-- Jika tabel finance_transactions belum ada, buat tabel sesuai struktur yang dipakai finance.php:
CREATE TABLE IF NOT EXISTS finance_transactions (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    type ENUM('Pemasukan', 'Pengeluaran') NOT NULL,
    category VARCHAR(100) NOT NULL,
    amount DECIMAL(14,2) NOT NULL,
    transaction_date DATE NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    order_id INT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_finance_order_id (order_id),
    KEY idx_finance_date (transaction_date),
    KEY idx_finance_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PENTING:
-- Jika tabel finance_transactions SUDAH ADA, jangan jalankan CREATE di atas saja
-- dan jangan hapus tabel. Jalankan dua perintah berikut satu per satu:
-- ALTER TABLE finance_transactions ADD COLUMN order_id INT NULL;
-- ALTER TABLE finance_transactions ADD UNIQUE KEY uq_finance_order_id (order_id);
