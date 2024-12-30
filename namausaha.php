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

// Ambil data dari tabel nama
$result = $conn->query("SELECT * FROM namausaha");

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
                    <h1>Identitas Usaha</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 fs-4">
                    <div class="table-responsive">
                        <table id="namaTable" class="table table-bordered table-striped table-hover">    
                            <tbody>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                while ($namausaha = $result->fetch_assoc()) {
                                    echo "<div class='d-flex align-items-start mb-4'>";
                                    
                                    // Bagian untuk menampilkan foto
                                    echo "<div class='me-3'>";
                                    // Ganti 'path/to/image.jpg' dengan path file gambar yang sesuai
                                    echo "<img src='foto/logo.jpg' alt='Foto Usaha' style='width: 420px; height: 520px; object-fit: cover; border-radius: 20px;'>";
                                    echo "</div>";

                                    // Bagian untuk menampilkan informasi dalam bentuk list
                                    echo "<div>";
                                    echo "<p><strong> Nama:</strong> " . htmlspecialchars($namausaha['nama']) . "</p>";
                                    echo "<p><strong> Alamat:</strong> " . htmlspecialchars($namausaha['alamat']) . "</p>";
                                    echo "<p><strong> No Telepon:</strong> " . htmlspecialchars($namausaha['notelepon']) . "</p>";
                                    echo "<p><strong> Fax:</strong> " . htmlspecialchars($namausaha['fax']) . "</p>";
                                    echo "<p><strong> Email:</strong> " . htmlspecialchars($namausaha['email']) . "</p>";
                                    echo "<p><strong> NPWP:</strong> " . htmlspecialchars($namausaha['npwp']) . "</p>";
                                    echo "<p><strong> Bank:</strong> " . htmlspecialchars($namausaha['bank']) . "</p>";
                                    echo "<p><strong> No Account:</strong> " . htmlspecialchars($namausaha['noaccount']) . "</p>";
                                    echo "<p><strong> Atas Nama:</strong> " . htmlspecialchars($namausaha['atasnama']) . "</p>";
                                    echo "<p><strong> Pemimpin:</strong> " . htmlspecialchars($namausaha['pimpinan']) . "</p>";
                                    
                                    // Tombol edit
                                    echo "<button class='btn btn-warning btn-sm edit-btn mt-2' data-bs-toggle='modal' data-bs-target='#editnamausahaModal' data-nama='" . htmlspecialchars($namausaha['nama']) . "' data-alamat='" . htmlspecialchars($namausaha['alamat']) . "' data-notelepon='" . htmlspecialchars($namausaha['notelepon']) ."' data-fax='" . htmlspecialchars($namausaha['fax']) ."' data-email='" . htmlspecialchars($namausaha['email']) ."' data-npwp='" . htmlspecialchars($namausaha['npwp']) ."' data-bank='" . htmlspecialchars($namausaha['bank']) ."' data-noaccount='" . htmlspecialchars($namausaha['noaccount']) ."' data-atasnama='" . htmlspecialchars($namausaha['atasnama']) ."' data-pimpinan='" . htmlspecialchars($namausaha['pimpinan']) . "'><i class='fas fa-edit'></i> Edit</button>";
                                    
                                    echo "</div>"; // Tutup div informasi
                                    echo "</div>"; // Tutup div d-flex
                                }
                            } else {
                                echo "<p class='text-center'>No data found</p>";
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

<!-- Modal Edit nama -->
<div class="modal fade" id="editnamausahaModal" tabindex="-1" aria-labelledby="editnamausahaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editnamausahaModalLabel">Edit Nama Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_namausaha.php" method="post">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="edit_alamat" name="alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_notelepon" class="form-label">No Telepon</label>
                        <input type="text" class="form-control" id="edit_notelepon" name="notelepon" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fax" class="form-label">Fax</label>
                        <input type="text" class="form-control" id="edit_fax" name="fax" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_npwp" class="form-label">NPWP</label>
                        <input type="text" class="form-control" id="edit_npwp" name="npwp" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_bank" class="form-label">Bank</label>
                        <input type="text" class="form-control" id="edit_bank" name="bank" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_noaccount" class="form-label">No Account</label>
                        <input type="text" class="form-control" id="edit_noaccount" name="noaccount" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_atasnama" class="form-label">Atas Nama</label>
                        <input type="text" class="form-control" id="edit_atasnama" name="atasnama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_pimpinan" class="form-label">Pimpinan</label>
                        <input type="text" class="form-control" id="edit_pimpinan" name="pimpinan" required>
                    </div>
                    <!-- tinggal tambahin untuk edit nama -->
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

            $('#namaTable').DataTable().destroy();
            $('#namaTable').DataTable({
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
        $('#editnamausahaModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var nama = button.data('nama');
            var alamat = button.data('alamat');
            var notelepon = button.data('notelepon');
            var fax = button.data('fax');
            var email = button.data('email');
            var npwp = button.data('npwp');
            var bank = button.data('bank');
            var noaccount = button.data('noaccount');
            var atasnama = button.data('atasnama');
            var pimpinan = button.data('pimpinan');
           
            var modal = $(this);
            modal.find('#edit_nama').val(nama);
            modal.find('#edit_alamat').val(alamat);
            modal.find('#edit_notelepon').val(notelepon);
            modal.find('#edit_fax').val(fax);
            modal.find('#edit_email').val(nama);
            modal.find('#edit_npwp').val(npwp);
            modal.find('#edit_bank').val(bank);
            modal.find('#edit_noaccount').val(noaccount);
            modal.find('#edit_atasnama').val(atasnama);
            modal.find('#edit_pimpinan').val(pimpinan);
        });

        // Show message if it exists in the session
        <?php if ($message): ?>
            Swal.fire({
                title: '<?php echo $message['type'] === 'success' ? 'Success!' : 'Error!'; ?>',
                text: '<?php echo $message['text']; ?>',
                icon: '<?php echo $message['type'] === 'success' ? 'success' : 'error'; ?>'
            });
        <?php endif; ?>

                
    });
</script>
</body>
</html>
