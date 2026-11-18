
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tentang Kami | TahuNutrifeed</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
        /* PENCAPAIAN PRODUKSI MITRA */
        .production-section {
            padding: 45px 7%;
            background: #f7faf5;
        }

        .production-card {
            max-width: 1100px;
            margin: 0 auto;
            padding: 35px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 25px;
            background: #ffffff;
            border: 1px solid #dcebd8;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(29, 78, 36, 0.08);
        }

        .production-icon {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eaf6e9;
            border-radius: 16px;
            font-size: 30px;
        }

        .production-info h2 {
            margin: 8px 0 10px;
            color: #244d2b;
        }

        .production-info p {
            margin: 0;
            line-height: 1.7;
            color: #687568;
        }

        .production-highlight {
            min-width: 160px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #eaf6e9;
            border: 1px solid #c9e6c6;
            border-radius: 16px;
            color: #246b2b;
        }

        .production-highlight strong {
            font-size: 48px;
            line-height: 1.1;
            font-weight: 800;
        }

        .production-highlight span {
            font-size: 20px;
            font-weight: 700;
        }

        .production-highlight small {
            margin-top: 8px;
            font-size: 12px;
        }

        /* TAMPILAN HP */
        @media (max-width: 700px) {
            .production-section {
                padding: 30px 5%;
            }

            .production-card {
                grid-template-columns: 1fr;
                padding: 24px;
                gap: 18px;
            }

            .production-highlight {
                min-width: 0;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER / NAVIGASI -->
    <header class="nav">

        <a class="brand" href="index.php">
            <span class="leaf"></span>
            TahuNutrifeed
        </a>

        <nav>
            <a href="index.php">Beranda</a>
            <a href="produk.php">Produk</a>
            <a class="active" href="tentang.php">Tentang Kami</a>
            <a href="dampak.php">Dampak</a>
        </nav>

        <a
            class="btn small"
            href="https://wa.me/6287765470125"
        >
            Hubungi Kami ↗
        </a>

    </header>


    <!-- BANNER TENTANG KAMI -->
    <section class="page-banner">

        <span class="eyebrow">
            TENTANG TAHUNUTRIFEED
        </span>

        <h1>
            Mengubah sisa produksi menjadi peluang.
        </h1>

        <p>
            Gagasan pemanfaatan ampas tahu untuk mendukung
            nilai tambah usaha lokal.
        </p>

    </section>


    <!-- PROFIL USAHA -->
    <main class="section about">

        <div class="about-mark">
            <img
                src="assets/usaha.jpeg"
                alt="Profil UD. Tahu Yaya"
            >
        </div>

        <div>

            <h2>Profil UD. Tahu Yaya</h2>

            <p>
                UD. Tahu Yaya berlokasi di Kelurahan Abian Tubuh,
                Kecamatan Sandubaya, Kota Mataram. Proyek ini
                mengangkat potensi pemanfaatan limbah ampas tahu
                menjadi produk pakan ternak bernilai ekonomis.
            </p>

            <p>
                Informasi pada website ini merupakan pengenalan
                proyek. Detail produk, proses pengolahan, komposisi,
                keamanan pakan, dan cara penggunaan harus
                dikonfirmasi berdasarkan data produksi yang benar.
            </p>

        </div>

    </main>


    <!-- PENCAPAIAN PRODUKSI MITRA -->
    <section class="production-section">

        <div class="production-card">

            <div class="production-icon">
                🌱
            </div>

            <div class="production-info">

                <span class="eyebrow">
                    PENCAPAIAN MITRA
                </span>

                <h2>Produksi Telah Mencapai</h2>

                <p>
                    UD. Tahu Yaya telah mencapai produksi
                    pakan ternak berbahan
                    ampas tahu sebagai upaya pemanfaatan
                    limbah produksi menjadi produk bernilai ekonomi.
                </p>

            </div>

            <div class="production-highlight">

                <strong>250</strong>

                <span>KG</span>

                <small>Total Produksi</small>

            </div>

        </div>

    </section>


    <!-- LOKASI USAHA -->
    <section class="location-section">

        <div class="location-heading">

            <span class="eyebrow">
                TEMUKAN KAMI
            </span>

            <h2>Lokasi UD. Tahu Yaya</h2>

            <p>
                Kunjungi lokasi usaha kami di Abian Tubuh,
                Kecamatan Sandubaya, Kota Mataram.
            </p>

        </div>

        <div class="location-content">

            <div class="location-map">

                <iframe
                    src="https://maps.google.com/maps?q=UD.%20Tahu%20Yaya%2C%20Abian%20Tubuh%2C%20Sandubaya%2C%20Mataram%2C%20Nusa%20Tenggara%20Barat&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    width="100%"
                    height="380"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi UD. Tahu Yaya di Google Maps"
                ></iframe>

            </div>

            <div class="location-info">

                <div class="location-icon">
                    📍
                </div>

                <h3>UD. Tahu Yaya</h3>

                <p>
                    Kelurahan Abian Tubuh, Kecamatan Sandubaya,
                    Kota Mataram, Nusa Tenggara Barat.
                </p>

                <a
                    class="btn"
                    href="https://maps.app.goo.gl/tQaEiGzVAAoBiXjH9?g_st=aw"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Buka Petunjuk Arah ↗
                </a>

            </div>

        </div>

    </section>


    <!-- FOOTER -->
    <footer>

        <a class="brand" href="index.php">
            TahuNutrifeed
        </a>

        <p>
            UD. Tahu Yaya · Abian Tubuh, Sandubaya, Kota Mataram
        </p>

    </footer>

</body>
</html>
