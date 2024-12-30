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

// Ambil data dari tabel hutang
$result = $conn->query("SELECT * FROM tabelhutang");

// Dapatkan nomor urut terbaru untuk idhutang baru
$stmt = $conn->query("SELECT idhutang FROM tabelhutang ORDER BY idhutang DESC LIMIT 1");
$latestidhutang = $stmt->fetch_assoc();
$urut = 1;
if ($latestidhutang) {
    $latestNumber = (int) substr($latestidhutang['idhutang'], 1);
    $urut = $latestNumber + 1;
}
$newidhutang = 'H' . str_pad($urut, 3, '0', STR_PAD_LEFT);

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
                    <h4>Hutang</h4>
                    <div>
                        <button type="button" class="btn btn-primary mb-3 mr-2" data-bs-toggle="modal" data-bs-target="#addhutangModal"><i class='fas fa-plus'></i> Add </button>
                        <button type="button" class="btn btn-secondary mb-3" id="printButton"><i class='fas fa-print'></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="hutangTable" class="table table-bordered table-striped table-hover">    
                            <thead class="text-center">
                                <tr style="background-color:#f3f6">
                                    <th>No</th>
                                    <th>Id Hutang</th>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Hutang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    $no = 1;
                                    while ($tabelhutang = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $no++ . "</td>";
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelhutang['idhutang']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelhutang['nama']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelhutang['tanggal']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tabelhutang['jumlah']) . "</td>";
                                        echo "<td class='text-center'>";
                                        echo "<div class='d-flex justify-content-center'>";
                                        echo "<button class='btn btn-warning btn-sm edit-btn mr-1' data-bs-toggle='modal' data-bs-target='#edithutangModal' data-id='" . htmlspecialchars($tabelhutang['idhutang']) . "' data-nama='" . htmlspecialchars($tabelhutang['nama']) . "' data-tanggal='" . htmlspecialchars($tabelhutang['tanggal']) .  "' data-jumlah='" . htmlspecialchars($tabelhutang['jumlah']) . "'><i class='fas fa-edit'></i> Edit</button>";
                                        echo "<button class='btn btn-danger btn-sm delete-btn' data-id='" . htmlspecialchars($tabelhutang['idhutang']) . "'><i class='fas fa-trash'></i> Delete</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No data found</td></tr>";
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

<!-- Modal Add hutang -->
<div class="modal fade" id="addhutangModal" tabindex="-1" aria-labelledby="addhutangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addhutangModalLabel">Add Hutang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_hutang.php" method="post">
                    <div class="mb-3">
                        <label for="idhutang" class="form-label">Id Hutang</label>
                        <input type="text" class="form-control" id="idhutang" name="idhutang" value="<?php echo htmlspecialchars($newidhutang); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah Hutang</label>
                        <input type="text" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                                <!-- tinggal tambahin untuk add hutang -->
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit hutang -->
<div class="modal fade" id="edithutangModal" tabindex="-1" aria-labelledby="edithutangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edithutangModalLabel">Edit Hutang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_hutang.php" method="post">
                    <div class="mb-3">
                        <label for="edit_idhutang" class="form-label">Id Hutang</label>
                        <input type="text" class="form-control" id="edit_idhutang" name="idhutang" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jumlah" class="form-label">Jumlah Hutang</label>
                        <input type="text" class="form-control" id="edit_jumlah" name="jumlah" required>
                    </div>
                    <!-- tinggal tambahin untuk edit hutang -->
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

            $('#hutangTable').DataTable().destroy();
            $('#hutangTable').DataTable({
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

        // Populate edit modal with data
        $('#edithutangModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idhutang = button.data('id');
            var nama = button.data('nama');
            var tanggal = button.data('tanggal');
            var jumlah = button.data('jumlah');
            
            var modal = $(this);
            modal.find('#edit_idhutang').val(idhutang);
            modal.find('#edit_nama').val(nama);
            modal.find('#edit_tanggal').val(tanggal);
            modal.find('#edit_jumlah').val(jumlah);
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
            var idhutang = $(this).data('id');
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
                        url: 'delete_hutang.php',
                        type: 'POST',
                        data: { idhutang: idhutang },
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
        //Print ke PDF        
        $(document).ready(function() {
            // Other existing scripts...

            // Handle print button click
            $('#printButton').click(function() {
                window.location.href = 'print_hutang.php';
            });
        });
    });
</script>
</body>
</html>
