<?php $this->setLayoutVar('title', '新規ユーザ登録画面') ?>

<form action="<?php echo $base_url; ?>/user/register" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
    <?php if(isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', ['errors' => $errors]); ?>
    <?php endif; ?>
<p>
    <table>
        <tr>
            <th>新規ユーザ登録</th>
        </tr>
        <tr>
            <th>メールアドレス(ユーザID)</th>
            <td><input type="text" class="form-control" name="mail" value="<?php echo $this->escape($mail); ?>" /></td>
        </tr>
        <tr>
            <th>パスワード</th>
            <td><input type="password" class="form-control" name="password" value="<?php echo $this->escape($password); ?>" /></td>
        </tr>
        <tr>
            <th>パスワード(再確認)</th>
            <td><input type="password" class="form-control" name="passwordCheck" value="<?php echo $this->escape($passwordCheck); ?>" /></td>
        </tr>
        <tr>
            <th>社員名</th>
            <td><input type="text" class="form-control" name="name" value="<?php echo $this->escape($name); ?>" /></td>
        </tr>
        <tr>
            <th>部署</th>
            <td><select class="form-control" name="department_id">
                <?php foreach ($depts as $dept): ?>
                    <option value="<?php echo $dept['department_id']; ?>" 
                        <?php if('department_id' == $dept['department_id']) echo "selected=\"selected\""; ?>>
                        <?php echo $dept['depart_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select></td>
        </tr>
        <tr>
            <th></th>
            <td><input type="submit" class="btn" value="登録" /></td>
        </tr>
    </table>
</p>
</form>