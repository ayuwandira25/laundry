<?php
$page_title = 'Data Admin';
$current_page = 'users';
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../layouts/admin_header.php';
$users = getAll('users');
?>

<style>
    .page-wrapper {
        padding: 20px;
    }

    .content-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 25px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        background: #1e293b;
        color: #fff;
        padding: 15px;
        text-align: left;
        font-size: 14px;
        font-weight: 600;
    }

    table td {
        padding: 15px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
        color: #334155;
    }

    table tr:hover {
        background: #f8fafc;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #dbeafe;
        color: #0c4a6e;
    }

    .empty-data {
        padding: 40px 20px;
        text-align: center;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        font-size: 15px;
    }

    @media (max-width: 768px) {
        .content-card {
            padding: 20px;
        }

        .section-title {
            font-size: 20px;
        }

        table th,
        table td {
            padding: 12px 8px;
            font-size: 13px;
        }
    }
</style>

<div class="page-wrapper">

    <section class="content-card">

        <h2 class="section-title">Data Admin</h2>

        <?php if (empty($users)): ?>

            <div class="empty-data">
                👤 Belum ada data admin.
            </div>

        <?php else: ?>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <span class="badge">
                                        #<?php echo htmlspecialchars($user['id']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['nama']); ?></td>
                                <td>
                                    <span class="badge" style="background: #fce7f3; color: #be185d;">
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>

</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>