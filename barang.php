<?php
session_start();
require 'config.php';

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

$iduser = $_SESSION['iduser'];

// Ambil data dari tabel barang
$barang = $conn->query("SELECT * FROM tabelbarang");

// Ambil data kategori
$idkategori_result = $conn->query("SELECT idkategori, kategori FROM tabelkategori");

// Ambil data merek
$idmerek_result = $conn->query("SELECT idmerek, merek FROM tabelmerek");

// Ambil data user dari database
$stmt = $conn->prepare("SELECT username, email FROM login WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// Ambil data nama usaha dan harga dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();

// Dapatkan nomor urut terbaru untuk idbarang baru
$stmt = $conn->query("SELECT idbarang FROM tabelbarang ORDER BY idbarang DESC LIMIT 1");
$latestidbarang = $stmt->fetch_assoc();
$urut = 1;
if ($latestidbarang) {
    $latestNumber = (int) substr($latestidbarang['idbarang'], 1);
    $urut = $latestNumber + 1;
}
$newidbarang = 'B' . str_pad($urut, 3, '0', STR_PAD_LEFT);

// Simpan pesan ke variabel dan hapus dari session
$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Handle deletion
if (isset($_POST['delete_idbarang'])) {
    $delete_idbarang = $_POST['delete_idbarang'];
    $stmt = $conn->prepare("DELETE FROM tabelbarang WHERE idbarang = ?");
    $stmt->bind_param("s", $delete_idbarang);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Barang berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Gagal menghapus barang.";
    }

    $stmt->close();
    header("Location: your_page.php"); // Redirect back to the page
    exit();
}
?>

<link rel="stylesheet" href="#">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php require 'head.php'; ?>
<div class="wrapper">
    <header>
        <h4><?php echo htmlspecialchars($namaUsaha ?? ''); ?></h4>
        <p><?php echo htmlspecialchars($alamatUsaha ?? ''); ?></p>
    </header>
    <?php include 'sidebar.php'; ?>
    <div class="content" id="content">
        <div class="container-fluid mt-3" style="margin-left:15px">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h4>Barang</h4>
                    <div>
                        <button type="button" class="btn btn-primary mb-3 mr-2" data-bs-toggle="modal" data-bs-target="#addBarangModal">
                            <i class='fas fa-plus'></i> Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="barangTable" class="table table-striped table-bordered table-hover">    
                            <thead class="text-center table-info">
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th> 
                                    <th>Id Barang</th>
                                    <th>Id Kategori</th>
                                    <th>Id Merek</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Keterangan</th>
                                    <th>Created At</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            <?php

                                // Koneksi ke database
                                $conn = new mysqli("localhost", "root", "", "db_uts_penjualan");

                                // Periksa koneksi
                                if ($conn->connect_error) {
                                    die("Koneksi gagal: " . $conn->connect_error);
                                }
                                // Query SQL
                                $sql = "SELECT 
                                            tabelbarang.idbarang, 
                                            tabelbarang.nama, 
                                            tabelbarang.idkategori, 
                                            tabelkategori.kategori, 
                                            tabelbarang.idmerek, 
                                            tabelmerek.merek, 
                                            tabelbarang.harga, 
                                            tabelbarang.stok, 
                                            tabelbarang.keterangan 
                                        FROM 
                                            tabelbarang
                                        LEFT JOIN 
                                            tabelmerek ON tabelbarang.idmerek = tabelmerek.idmerek
                                        LEFT JOIN 
                                            tabelkategori ON tabelbarang.idkategori = tabelkategori.idkategori";

                                $result = $conn->query($sql);


                                if ($result && $result->num_rows > 0) {
                                    $no = 1;
                                    while ($tabelbarang = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $no++ . "</td>"; // Nomor urut

                                        // Menampilkan foto barang
                                        $idbarang = $tabelbarang['idbarang'];
                                        $fotoPath = 'foto/' . $idbarang . '.jpg';
                                        echo "<td class='text-center'>";
                                        if (file_exists($fotoPath)) {
                                            echo "<img src='" . htmlspecialchars($fotoPath) . "' alt='foto barang' style='width:50px;height:50px;'>";
                                        } else {
                                            echo "tidak ada ft";
                                        }
                                        echo "</td>";

                                        // Menampilkan data barang lainnya
                                        echo "<td>" . htmlspecialchars($tabelbarang['idbarang']) . "</td>";
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelbarang['nama']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelbarang['idkategori']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelbarang['kategori']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelbarang['idmerek']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelbarang['merek']) . "</td>";
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelbarang['harga']) . "</td>";
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelbarang['stok']) . "</td>";
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelbarang['keterangan']) . "</td>";
                                        
                                        // Tombol Edit dan Delete
                                        echo "<td class='text-center'>";
                                        echo "<div class='d-flex justify-content-center'>";
                                        echo "<button class='btn btn-warning btn-sm edit-btn' data-bs-toggle='modal' data-bs-target='#editbarangModal' 
                                                data-idbarang='" . htmlspecialchars($tabelbarang['idbarang']) . "'  
                                                data-nama='" . htmlspecialchars($tabelbarang['nama']) . "' 
                                                data-idkategori='" . htmlspecialchars($tabelbarang['idkategori']) . "' 
                                                data-idmerek='" . htmlspecialchars($tabelbarang['idmerek']) . "' 
                                                data-harga='" . htmlspecialchars($tabelbarang['harga']) . "' 
                                                data-stok='" . htmlspecialchars($tabelbarang['stok']) . "' 
                                                data-keterangan='" . htmlspecialchars($tabelbarang['keterangan']) . "'>
                                                <i class='fas fa-edit'></i> Edit
                                            </button>";
                                        echo "<button class='btn btn-danger btn-sm delete-btn' data-id='" . htmlspecialchars($tabelbarang['idbarang']) . "'><i class='fas fa-trash'></i> Delete</button>";
                                        
                                        echo "<button class='btn btn-success btn-sm print-btn' data-id='" . htmlspecialchars($tabelbarang['idbarang']) . "'><i class='fas fa-trash'></i> print</button>";

                                       
                                        // echo "<button class='btn btn-primary btn-sm print-btn' onclick='printItem(" . htmlspecialchars($tabelbarang['idbarang']) . ")'><i class='fas fa-print'></i> Print</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='11' class='text-center'>No data found</td></tr>";
                                }
                                // Tutup koneksi
                                $conn->close();

                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require 'footer.php'; ?>
</div>

<!-- Modal Add Barang -->
<div class="modal fade" id="addBarangModal" tabindex="-1" aria-labelledby="addBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBarangModalLabel">Add Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_barang.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="foto" class="form-label">Upload Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" required>
                    </div>
                    <div class="mb-3">
                        <label for="idbarang" class="form-label">Id Barang</label>
                        <input type="text" class="form-control" id="idbarang" name="idbarang" value="<?php echo htmlspecialchars($newidbarang); ?>" readonly>
                    </div>
                    <div class="mb-3">        
                        <label for="idkategori" class="form-label">Id Kategori</label>
                        <select class="form-select" id="idkategori" name="idkategori" required>
                            <?php
                            if ($idkategori_result->num_rows > 0) {
                                while($row = $idkategori_result->fetch_assoc()) {
                                    echo "<option value='" . $row['idkategori'] . "'>" . $row['kategori'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="idmerek" class="form-label">Id Merek</label>
                        <select class="form-select" id="idmerek" name="idmerek" required>
                            <?php
                            if ($idmerek_result->num_rows > 0) {
                                while($row = $idmerek_result->fetch_assoc()) {
                                    echo "<option value='" . $row['idmerek'] . "'>" . $row['merek'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Barang -->
<div class="modal fade" id="editBarangModal" tabindex="-1" aria-labelledby="editBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBarangModalLabel">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_barang.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="edit_idbarang" class="form-label">Id Barang</label>
                        <input type="text" class="form-control" id="edit_idbarang" name="idbarang" readonly>
                    </div>
                    <div class="mb-3">        
                        <label for="edit_idkategori" class="form-label">Id Kategori</label>
                        <select class="form-select" id="edit_idkategori" name="idkategori" required>
                            <?php
                            $idkategori_result->data_seek(0);
                            while($row = $idkategori_result->fetch_assoc()) {
                                echo "<option value='" . $row['idkategori'] . "'>" . $row['kategori'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_idmerek" class="form-label">Id Merek</label>
                        <select class="form-select" id="edit_idmerek" name="idmerek" required>
                            <?php
                            $idmerek_result->data_seek(0);
                            while($row = $idmerek_result->fetch_assoc()) {
                                echo "<option value='" . $row['idmerek'] . "'>" . $row['merek'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="edit_harga" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="edit_stok" name="stok" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle delete button click
    $(document).on('click', '.delete-btn', function() {
        var idbarang = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'Apa benar data tersebut dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete_barang.php',
                    type: 'POST',
                    data: { idbarang: idbarang },
                    success: function(response) {
                        Swal.fire('Deleted!', response, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr, error) {
                        Swal.fire('Error!', 'Terjadi kesalahan: ' + error, 'error');
                    }
                });
            }
        });
    });

    // Set values in the edit modal
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const idbarang = this.getAttribute('data-idbarang');
            const idkategori = this.getAttribute('data-idkategori');
            const idmerek = this.getAttribute('data-idmerek');
            const nama = this.getAttribute('data-nama');
            const harga = this.getAttribute('data-harga');
            const stok = this.getAttribute('data-stok');
            const keterangan = this.getAttribute('data-keterangan');

            document.getElementById('edit_idbarang').value = idbarang;
            document.getElementById('edit_idkategori').value = idkategori;
            document.getElementById('edit_idmerek').value = idmerek;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('edit_stok').value = stok;
            document.getElementById('edit_keterangan').value = keterangan;
        });
    
     //Print ke PDF 
     document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.print-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                window.open('print_barang.php?id=' + id, '_blank');
            });
        });
    });
});
</script>
