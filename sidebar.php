<div class="sidebar">
    <div class="user-info">
        <img src="foto/admin/<?php echo htmlspecialchars($username); ?>.jpg" alt="User Photo" class="user-photo">
        <p class="user-name"><?php echo htmlspecialchars($username); ?></p>
    </div>
    <ul>
        <li><a href="profiladmin.php"><span><i class="fas fa-user"></i> Profil</span></a></li>
        <li><a href="index.php"><span><i class="fas fa-home"></i> Home</span></a></li>
        <li><a href="namausaha.php"><span><i class="fas fa-building"></i> Identitas Usaha</span></a></li>
        <li>
            <a href="#" class="menu-toggle"><span><i class="fas fa-users"></i> Master</span><i class="fas fa-chevron-right arrow"></i></a>
            <ul class="sub-menu">
                <li><a href="kategori.php"><span> Kategori</span></a></li>
                <li><a href="merek.php"><span> Merek</span></a></li>
                <li><a href="barang.php"><span> Barang</span></a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="menu-toggle"><span><i class="fas fa-exchange-alt"></i> Transaksi</span><i class="fas fa-chevron-right arrow"></i></a>
            <ul class="sub-menu">
                <li><a href="penjualan.php"><span> Penjualan</span></a></li>
                <li><a href="pembelian.php"><span> Pembelian</span></a></li>
                <li><a href="hutang.php"><span> Hutang</span></a></li>
                <li><a href="piutang.php"><span> Piutang</span></a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="menu-toggle"><span><i class="fas fa-chart-line"></i> Report</span><i class="fas fa-chevron-right arrow"></i></a>
            <ul class="sub-menu">
                <li><a href="grafikpenjualan.php"><span> Grafik Penjualan</span></a></li>
                <li><a href="grafikpembelian.php"><span> Grafik pembelian</span></a></li>
                <li><a href="grafikpemasukan.php"><span> Grafik Pemasukan</span></a></li>
                <li><a href="grafikpengeluaran.php"><span> Grafik Pengeluaran</span></a></li>
                
            </ul>
        </li>
        <li><a href="logout.php"><span><i class="fas fa-sign-out-alt"></i> Logout</span></a></li>
    </ul>
    <div class="toggle-sidebar">
        <i class="fas fa-bars"></i>
    </div>
</div>
