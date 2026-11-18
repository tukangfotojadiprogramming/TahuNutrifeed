
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Dampak | TahuNutrifeed</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
        /* BACKGROUND HALAMAN */
        body {
            background: #fbfcf8;
        }

        /* BANNER */
        .page-banner {
            position: relative;
            overflow: hidden;
            padding: 90px 7%;
            background: linear-gradient(
                135deg,
                #edf7e9 0%,
                #f8fbf4 55%,
                #e0efdc 100%
            );
            text-align: center;
        }

        .page-banner::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            top: -120px;
            right: -60px;
            background: rgba(75, 135, 69, 0.08);
            border-radius: 50%;
        }

        .page-banner::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            bottom: -100px;
            left: -50px;
            background: rgba(75, 135, 69, 0.08);
            border-radius: 50%;
        }

        .page-banner .eyebrow,
        .page-banner h1,
        .page-banner p {
            position: relative;
            z-index: 1;
        }

        .page-banner .eyebrow {
            display: inline-block;
            padding: 9px 16px;
            background: #dcefd7;
            border: 1px solid #c9e3c2;
            border-radius: 50px;
            color: #356b36;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .page-banner h1 {
            max-width: 750px;
            margin: 22px auto 16px;
            color: #234b2b;
            font-size: clamp(32px, 5vw, 52px);
            line-height: 1.15;
            letter-spacing: -1.5px;
        }

        .page-banner p {
            max-width: 650px;
            margin: 0 auto;
            color: #657565;
            font-size: 16px;
            line-height: 1.8;
        }

        /* BAGIAN KARTU DAMPAK */
        main.section {
            padding-top: 65px;
            padding-bottom: 75px;
        }

        .impact-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .impact-grid article {
            position: relative;
            overflow: hidden;
            padding: 32px;
            background: #ffffff;
            border: 1px solid #e4ebdf;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(35, 75, 43, 0.05);
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .impact-grid article:hover {
            transform: translateY(-6px);
            border-color: #b9d7b1;
            box-shadow: 0 16px 35px rgba(35, 75, 43, 0.11);
        }

        /* IKON KARTU */
        .impact-grid article > span {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            background: #eaf5e7;
            border-radius: 16px;
            font-size: 28px;
        }

        .impact-grid article:nth-child(1) > span::before {
            content: "♻";
        }

        .impact-grid article:nth-child(2) > span {
            background: #f7f0d9;
        }

        .impact-grid article:nth-child(3) > span {
            background: #e8effb;
        }

        .impact-grid article:nth-child(4) > span {
            background: #e5f4ed;
        }

        .impact-grid article h3 {
            margin: 0 0 12px;
            color: #244b2c;
            font-size: 21px;
            line-height: 1.4;
        }

        .impact-grid article p {
            margin: 0;
            color: #687568;
            font-size: 15px;
            line-height: 1.85;
        }

        /* AKSEN KECIL KARTU */
        .impact-grid article::after {
            content: "";
            position: absolute;
            right: -25px;
            bottom: -35px;
            width: 110px;
            height: 110px;
            border: 18px solid rgba(86, 143, 76, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        /* CATATAN */
        .note {
            max-width: 1100px;
            margin: 30px auto 0;
            padding: 18px 22px;
            background: #f0f6eb;
            border-left: 4px solid #6b9c5e;
            border-radius: 8px;
            color: #526b50;
            font-size: 13px;
            line-height: 1.8;
        }



        /* RESPONSIVE */
        @media (max-width: 700px) {
            .page-banner {
                padding: 65px 6%;
            }

            .page-banner h1 {
                letter-spacing: -0.5px;
            }

            main.section {
                padding: 40px 5%;
            }

            .impact-grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .impact-grid article {
                padding: 25px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .impact-grid article {
                transition: none;
            }

            .impact-grid article:hover {
                transform: none;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header class="nav">

        <a class="brand" href="index.php">
            <span class="leaf"></span>
            TahuNutrifeed
        </a>

        <nav>
            <a href="index.php">Beranda</a>
            <a href="produk.php">Produk</a>
            <a href="tentang.php">Tentang Kami</a>
            <a class="active" href="dampak.php">Dampak</a>
        </nav>

        <a
            class="btn small"
            href="https://wa.me/6287765470125"
        >
            Hubungi Kami ↗
        </a>

    </header>


    <!-- BANNER -->
    <section class="page-banner">

        <span class="eyebrow">
            DAMPAK YANG DIHARAPKAN
        </span>

        <h1>
            Dari limbah menuju nilai tambah.
        </h1>

        <p>
            Pemanfaatan limbah dapat membuka peluang ekonomi
            sekaligus mendukung pengelolaan sisa produksi.
        </p>

    </section>


    <!-- KARTU DAMPAK -->
    <main class="section">

        <div class="impact-grid">

            <article>
                <span>♻</span>

                <h3>Pemanfaatan Limbah</h3>

                <p>
                    Mendorong ampas tahu agar berpotensi
                    dimanfaatkan, bukan langsung dibuang.
                </p>
            </article>

            <article>
                <span>🌾</span>

                <h3>Alternatif Bahan Pakan</h3>

                <p>
                    Mengeksplorasi potensi ampas tahu sebagai
                    bahan yang dapat diolah untuk pakan ternak.
                </p>
            </article>

            <article>
                <span>🤝</span>

                <h3>Nilai Ekonomi Lokal</h3>

                <p>
                    Membuka peluang pengembangan produk dan
                    promosi bagi usaha lokal.
                </p>
            </article>

            <article>
                <span>🌱</span>

                <h3>Kesadaran Lingkungan</h3>

                <p>
                    Meningkatkan perhatian terhadap pengelolaan
                    sisa produksi secara bertanggung jawab.
                </p>
            </article>

        </div>

        <p class="note">
            Dampak tersebut merupakan tujuan yang diharapkan
            dari pengembangan TahuNutrifeed dan perlu dievaluasi
            berdasarkan pelaksanaan serta data usaha yang nyata.
        </p>

    </main>


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
