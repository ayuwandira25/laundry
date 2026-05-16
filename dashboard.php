<?php
$page_title = 'Dashboard';
$current_page = 'dashboard';
require_once __DIR__ . '/layouts/admin_header.php';
?>

                <!-- Welcome -->
                <div class="welcome-card">
                    <h2>
                        Selamat Datang,
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </h2>

                    <p>
                        Sistem Informasi Laundry untuk membantu pengelolaan pelanggan,
                        pesanan, pembayaran, dan laporan secara mudah dan efisien.
                    </p>
                </div>

                <?php if ($is_admin): ?>

                <!-- Statistik -->
                <div class="row">

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-soft-dark">
                            <div class="inner">
                                <h3><?php echo count(getAll('pelanggan')); ?></h3>
                                <p>Total Pelanggan</p>
                            </div>

                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-soft-blue">
                            <div class="inner">
                                <h3><?php echo count(getAll('karyawan')); ?></h3>
                                <p>Total Karyawan</p>
                            </div>

                            <div class="icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-soft-gray">
                            <div class="inner">
                                <h3><?php echo count(getAll('pesanan')); ?></h3>
                                <p>Total Pesanan</p>
                            </div>

                            <div class="icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-soft-light">
                            <div class="inner">
                                <h3><?php echo count(getAll('transaksi')); ?></h3>
                                <p>Total Transaksi</p>
                            </div>

                            <div class="icon">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <?php endif; ?>

<?php require_once __DIR__ . '/layouts/admin_footer.php'; ?>