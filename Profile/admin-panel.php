<?php
session_start();
require '../php/db.php';

// Проверка авторизации и роли admin
if (!isset($_SESSION['user_id'])) {
    header('Location: ../backend/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    echo "Доступ запрещён";
    exit();
}

// --- Загрузка данных ---

$users = $pdo->query("SELECT id, username, email, role FROM users ORDER BY id ASC")->fetchAll();
$products = $pdo->query("SELECT id, name, price, description FROM products ORDER BY id ASC")->fetchAll();

$tables = [];
$res = $pdo->query("SHOW TABLES");
while ($row = $res->fetch(PDO::FETCH_NUM)) {
    $tables[] = $row[0];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Админ-панель</title>
    <link rel="stylesheet" href="admin-panel.css">
</head>
<body>
    <div class="top-bar">
        <h1>Админ-панель</h1>
        <a class="logout" href="user-profile.php">Выйти</a>
    </div>

    <section class="section">
        <h2>Пользователи</h2>
        <form id="add-user-form" class="inline">
            <input type="text" name="username" placeholder="Имя" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <select name="role" required>
                <option value="user">Пользователь</option>
                <option value="admin">Администратор</option>
            </select>
            <button type="submit">Добавить</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Имя</th><th>Email</th><th>Роль</th><th>Действия</th>
                </tr>
            </thead>
            <tbody id="users-tbody">
                <?php foreach ($users as $u): ?>
                <tr data-id="<?= $u['id'] ?>">
                    <td><?= $u['id'] ?></td>
                    <td><input type="text" name="username" value="<?= htmlspecialchars($u['username']) ?>" required></td>
                    <td><input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required></td>
                    <td>
                        <select name="role" required>
                            <option value="user" <?= $u['role']=='user'?'selected':'' ?>>Пользователь</option>
                            <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>Администратор</option>
                        </select>
                    </td>
                    <td>
                        <button class="save-user-btn">Сохранить</button>
                        <button class="delete-user-btn" style="background:#f44336;color:#fff;">Удалить</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="section">
        <h2>Товары</h2>
        <form id="add-product-form" class="inline">
            <input type="text" name="name" placeholder="Название" required>
            <input type="number" step="0.01" name="price" placeholder="Цена" required>
            <input type="text" name="description" placeholder="Описание" required>
            <button type="submit">Добавить</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Название</th><th>Цена</th><th>Описание</th><th>Действия</th>
                </tr>
            </thead>
            <tbody id="products-tbody">
                <?php foreach ($products as $p): ?>
                <tr data-id="<?= $p['id'] ?>">
                    <td><?= $p['id'] ?></td>
                    <td><input type="text" name="name" value="<?= htmlspecialchars($p['name']) ?>" required></td>
                    <td><input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" required></td>
                    <td><input type="text" name="description" value="<?= htmlspecialchars($p['description']) ?>" required></td>
                    <td>
                        <button class="save-product-btn">Сохранить</button>
                        <button class="delete-product-btn" style="background:#f44336;color:#fff;">Удалить</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="section">
        <h2>Другие таблицы базы</h2>
        <?php foreach ($tables as $table):
            if (in_array($table, ['users', 'products'])) continue;
            $rows = $pdo->query("SELECT * FROM `$table` LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
            if (!$rows) continue;
            $columns = array_keys($rows[0]);
        ?>
        <h3><?= htmlspecialchars($table) ?></h3>
        <table>
            <thead><tr>
                <?php foreach ($columns as $col): ?>
                <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
            </tr></thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                <tr>
                    <?php foreach ($columns as $col): ?>
                    <td><?= htmlspecialchars($row[$col]) ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endforeach; ?>
    </section>

<script>
// --- AJAX Добавление пользователя ---
document.getElementById('add-user-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const res = await fetch('admin-actions.php?action=add_user', {
        method: 'POST',
        body: formData
    });
    const data = await res.json();
    alert(data.message);
    if (data.success) {
        location.reload();
    }
});

// --- AJAX Сохранение пользователя ---
document.querySelectorAll('.save-user-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        const tr = btn.closest('tr');
        const id = tr.dataset.id;
        const username = tr.querySelector('input[name="username"]').value;
        const email = tr.querySelector('input[name="email"]').value;
        const role = tr.querySelector('select[name="role"]').value;

        const formData = new FormData();
        formData.append('user_id', id);
        formData.append('username', username);
        formData.append('email', email);
        formData.append('role', role);

        const res = await fetch('admin-actions.php?action=edit_user', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        alert(data.message);
        if (data.success) {
            location.reload();
        }
    });
});

// --- AJAX Удаление пользователя ---
document.querySelectorAll('.delete-user-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        if (!confirm('Удалить пользователя?')) return;

        const tr = btn.closest('tr');
        const id = tr.dataset.id;

        const formData = new FormData();
        formData.append('user_id', id);

        const res = await fetch('admin-actions.php?action=delete_user', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        alert(data.message);
        if (data.success) {
            location.reload();
        }
    });
});

// --- AJAX Добавление товара ---
document.getElementById('add-product-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const res = await fetch('admin-actions.php?action=add_product', {
        method: 'POST',
        body: formData
    });
    const data = await res.json();
    alert(data.message);
    if (data.success) {
        location.reload();
    }
});

// --- AJAX Сохранение товара ---
document.querySelectorAll('.save-product-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        const tr = btn.closest('tr');
        const id = tr.dataset.id;
        const name = tr.querySelector('input[name="name"]').value;
        const price = tr.querySelector('input[name="price"]').value;
        const description = tr.querySelector('input[name="description"]').value;

        const formData = new FormData();
        formData.append('product_id', id);
        formData.append('name', name);
        formData.append('price', price);
        formData.append('description', description);

        const res = await fetch('admin-actions.php?action=edit_product', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        alert(data.message);
        if (data.success) {
            location.reload();
        }
    });
});

// --- AJAX Удаление товара ---
document.querySelectorAll('.delete-product-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        if (!confirm('Удалить товар?')) return;

        const tr = btn.closest('tr');
        const id = tr.dataset.id;

        const formData = new FormData();
        formData.append('product_id', id);

        const res = await fetch('admin-actions.php?action=delete_product', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        alert(data.message);
        if (data.success) {
            location.reload();
        }
    });
});
</script>

</body>
</html>
