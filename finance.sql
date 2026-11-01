-- Jalankan sekali di phpMyAdmin pada database TahuNutrifeed.
-- Tabel ini terpisah dari tabel orders agar pesanan yang belum dibayar
-- tidak otomatis dianggap sebagai pemasukan usaha.
CREATE TABLE IF NOT EXISTS finance_transactions (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    type ENUM('Pemasukan', 'Pengeluaran') NOT NULL,
    category VARCHAR(100) NOT NULL,
    amount DECIMAL(14,2) NOT NULL,
    transaction_date DATE NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_finance_date (transaction_date),
    KEY idx_finance_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
