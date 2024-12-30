<?php
session_start();
require 'config.php';

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

$iduser = $_SESSION['iduser'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT username, email FROM login WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();

// Ambil data dari tabel penjualan
$result = $conn->query("SELECT * FROM tabelpenjualan");

// Dapatkan nomor urut terbaru untuk idpenjualan baru
$stmt = $conn->query("SELECT idpenjualan FROM tabelpenjualan ORDER BY idpenjualan DESC LIMIT 1");
$latestidpenjualan = $stmt->fetch_assoc();
$urut = 1;
if ($latestidpenjualan) {
    $latestNumber = (int) substr($latestidpenjualan['idpenjualan'], 1);
    $urut = $latestNumber + 1;
}
$newidpenjualan = 'SELL' . str_pad($urut, 3, '0', STR_PAD_LEFT);


// Simpan pesan ke variabel dan hapus dari session
$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<?php require 'head.php'; ?>
<div class="wrapper">
    <header>
        <h4><?php echo htmlspecialchars($namaUsaha); ?></h4>
        <p><?php echo htmlspecialchars($alamatUsaha); ?></p>
    </header>

    <?php include 'sidebar.php'; ?>
    <div class="content" id="content">
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h4>Penjualan</h4>
                    <div>
                        <button type="button" class="btn btn-primary mb-3 mr-2" data-bs-toggle="modal" data-bs-target="#addpenjualanModal"><i class='fas fa-plus'></i> Add </button>
                        <button type="button" class="btn btn-secondary mb-3" id="printButton"><i class='fas fa-print'></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="penjualanTable" class="table table-bordered table-striped table-hover">    
                            <thead class="text-center">
                                <tr style="background-color:#f3f6">
                                    <th>No</th>
                                    <th>Id Penjualan</th>
                                    <th>Id Barang</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Penjualan</th>
                                    <th>Dilayani Oleh</th>
                                    <th>Created At</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    $no = 1;
                                    while ($tabelpenjualan = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $no++ . "</td>";
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpenjualan['idpenjualan']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelpenjualan['idbarang']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelpenjualan['tanggal']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelpenjualan['jumlah']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelpenjualan['dilayani_oleh']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelpenjualan['created_at']) . "</td>";
                                        echo "<td class='text-center'>";
                                        echo "<div class='d-flex justify-content-center'>";

                                        echo "<button class='btn btn-warning btn-sm edit-btn mr-1' data-bs-toggle='modal' data-bs-target='#editpenjualanModal' data-id='" . htmlspecialchars($tabelpenjualan['idpenjualan']) . "' data-idbarang='" . htmlspecialchars($tabelpenjualan['idbarang']) . "' data-tanggal='" . htmlspecialchars($tabelpenjualan['tanggal']) . "' data-jumlah='" . htmlspecialchars($tabelpenjualan['jumlah']) . "' data-dilayani_oleh='" . htmlspecialchars($tabelpenjualan['dilayani_oleh']) . "' data-created_at='" . htmlspecialchars($tabelpenjualan['created_at']) . "'><i class='fas fa-edit'></i> Edit</button>";
                                        echo "<button class='btn btn-danger btn-sm delete-btn mr-1' data-id='" . htmlspecialchars($tabelpenjualan['idpenjualan']) . "'><i class='fas fa-trash'></i> Delete</button>";

                                        echo "<button class='btn btn-success btn-sm sell-btn' data-id='" . htmlspecialchars($tabelpenjualan['idpenjualan']) . "' data-idbarang='" . htmlspecialchars($tabelpenjualan['idbarang']) . "' data-jumlah='" . htmlspecialchars($tabelpenjualan['jumlah']) . "'><i class='fas fa-dollar-sign'></i> Jual</button>";
                                        
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>No data found</td></tr>";
                                }
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

<!-- Modal Add penjualan -->
<div class="modal fade" id="addpenjualanModal" tabindex="-1" aria-labelledby="addpenjualanModalLabel" aria-hidden="true">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addpenjualanModalLabel">Add penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_penjualan.php" method="post">
                    <div class="mb-3">
                        <label for="idpenjualan" class="form-label">Id penjualan</label>
                        <input type="text" class="form-control" id="idpenjualan" name="idpenjualan" value="<?php echo htmlspecialchars($newidpenjualan); ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="idbarang" class="form-label">Id Barang</label>
                        <select class="form-select" id="idbarang" name="idbarang" required>
                            <?php
                            // Pastikan koneksi database sudah dilakukan sebelumnya
                            $idbarang_query = "SELECT idbarang FROM tabelbarang"; // Ganti dengan nama tabel yang sesuai
                            $idbarang_result = $conn->query($idbarang_query);

                            if ($idbarang_result) {
                                if ($idbarang_result->num_rows > 0) {
                                    while($row = $idbarang_result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['idbarang']) . "'>" . htmlspecialchars($row['idbarang']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Tidak ada barang</option>";
                                }
                            } else {
                                echo "<option value=''>Error: " . htmlspecialchars($conn->error) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah Penjualan</label>
                        <input type="text" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="dilayani_oleh" class="form-label">Dilayani Oleh</label>
                        <input type="text" class="form-control" id="dilayani_oleh" name="dilayani_oleh" required>
                    </div>
                    <div class="mb-3">
                        <label for="created_at" class="form-label">Created At</label>
                        <input type="date" class="form-control" id="created_at" name="created_at" required>
                    </div>
                    
                                <!-- tinggal tambahin untuk add penjualan -->
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit penjualan -->
<div class="modal fade" id="editpenjualanModal" tabindex="-1" aria-labelledby="editpenjualanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editpenjualanModalLabel">Edit penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_penjualan.php" method="post">
                    <div class="mb-3">
                        <label for="edit_idpenjualan" class="form-label">Id Penjualan</label>
                        <input type="text" class="form-control" id="edit_idpenjualan" name="idpenjualan" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="edit_idbarang" class="form-label">Id Barang</label>
                        <select class="form-select" id="edit_idbarang" name="idbarang" required>
                            <?php
                            // Pastikan koneksi database sudah dilakukan sebelumnya
                            $idbarang_query = "SELECT idbarang FROM tabelbarang"; // Ganti dengan nama tabel yang sesuai
                            $idbarang_result = $conn->query($idbarang_query);

                            if ($idbarang_result) {
                                if ($idbarang_result->num_rows > 0) {
                                    while($row = $idbarang_result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['idbarang']) . "'>" . htmlspecialchars($row['idbarang']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Tidak ada barang</option>";
                                }
                            } else {
                                echo "<option value=''>Error: " . htmlspecialchars($conn->error) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jumlah" class="form-label">Jumlah penjualan</label>
                        <input type="text" class="form-control" id="edit_jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_dilayani_oleh" class="form-label">Dilayani Oleh</label>
                        <input type="text" class="form-control" id="edit_dilayani_oleh" name="dilayani_oleh" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_created_at" class="form-label">Created At</label>
                        <input type="date" class="form-control" id="edit_created_at" name="created_at" required>
                    </div>
                    
                    <!-- tinggal tambahin untuk edit penjualan -->
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Adjust DataTables' scrolling to avoid overlapping with the footer
        function adjustTableHeight() {
            var footerHeight = $('footer').outerHeight();
            var tableHeight = 'calc(100vh - 290px - ' + footerHeight + 'px)';

            $('#penjualanTable').DataTable().destroy();
            $('#penjualanTable').DataTable({
                "pagingType": "simple_numbers",
                "scrollY": tableHeight,
                "scrollCollapse": true,
                "paging": true
            });
        }

        // Call the function to adjust table height initially
        adjustTableHeight();

        // Adjust table height on window resize
        $(window).resize(function() {
            adjustTableHeight();
        });


        // Fungsi untuk memanggil ID penjualan baru
        function fetchNewIdPenjualan() {
            fetch('get_new_idpenjualan.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('idpenjualan').value = data.new_id;
                })
                .catch(error => console.error('Error fetching new ID:', error));
        }
        // Panggil fungsi fetchNewIdPenjualan saat modal dibuka
        var myModal = document.getElementById('addpenjualanModal');
        myModal.addEventListener('show.bs.modal', function (event) {
            fetchNewIdPenjualan();
        });



        // Populate edit modal with data
        $('#editpenjualanModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idpenjualan = button.data('id');
            var idbarang = button.data('idbarang');
            var tanggal = button.data('tanggal');
            var jumlah = button.data('jumlah');
            var dilayani_oleh = button.data('dilayani_oleh');
            var created_at = button.data('created_at');
            
            var modal = $(this);
            modal.find('#edit_idpenjualan').val(idpenjualan);
            modal.find('#edit_idbarang').val(idbarang);
            modal.find('#edit_tanggal').val(tanggal);
            modal.find('#edit_jumlah').val(jumlah);
            modal.find('#edit_dilayani_oleh').val(dilayani_oleh);
            modal.find('#edit_created_at').val(created_at);

        });

        // Show message if it exists in the session
        <?php if ($message): ?>
            Swal.fire({
                title: '<?php echo $message['type'] === 'success' ? 'Success!' : 'Error!'; ?>',
                text: '<?php echo $message['text']; ?>',
                icon: '<?php echo $message['type'] === 'success' ? 'success' : 'error'; ?>'
            });
        <?php endif; ?>

        // Handle delete button click
        $(document).on('click', '.delete-btn', function() {
            var idpenjualan = $(this).data('id');
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
                        url: 'delete_penjualan.php',
                        type: 'POST',
                        data: { idpenjualan: idpenjualan },
                        success: function(response) {
                            console.log(response); // Debugging
                            if (response.includes('Success')) {
                                Swal.fire(
                                    'Deleted!',
                                    'Data berhasil dihapus.',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText); // Debugging
                            Swal.fire(
                                'Error!',
                                'An error occurred: ' + error,
                                'error'
                            );
                        }
                    });
                }
            });   
        });        


        // Menangani klik pada tombol jual
        $(document).on('click', '.sell-btn', function() {
            var idpenjualan = $(this).data('id');
            var idbarang = $(this).data('idbarang');
            var jumlah = $(this).data('jumlah');

            Swal.fire({
                title: 'Konfirmasi Penjualan',
                text: 'Apakah Anda yakin ingin menjual ' + jumlah + ' unit?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, jual!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'sell_barang.php',
                        type: 'POST',
                        data: { idbarang: idbarang, jumlah: jumlah },
                        success: function(response) {
                            if (response.includes('Success')) {
                                Swal.fire('Terjual!', response, 'success').then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', response, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error!', 'Terjadi kesalahan: ' + error, 'error');
                        }
                    });
                }
            });
        });


        //Print ke PDF        
        $(document).ready(function() {
            // Other existing scripts...

            // Handle print button click
            $('#printButton').click(function() {
                window.location.href = 'print_penjualan.php';
            });
        });
    });
    

</script>
<!-- Pastikan Anda memuat JS Bootstrap jika belum -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
