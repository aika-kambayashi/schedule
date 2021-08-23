<?php

class UserController extends Controller {
    
    protected $auth_actions = ['logout'];

    public function registerAction() { 
        if ($this->session->isAuthenticated()) {
            return $this->redirect( '/' );
        }
        $mail = '';
        $password = '';
        $passwordCheck = '';
        $name = '';
        $depts = $this->db_manager->get('User')->getDepartments();
        $errors = [];

        if ($this->request->isPost()) {
            $token = $this->request->getPost('_token');
            if (!$this->checkCsrfToken('user/register', $token)) {
                return $this->redirect('/user/register');
            }
            $mail = $this->request->getPost('mail');
            $password = $this->request->getPost('password');
            $passwordCheck = $this->request->getPost('passwordCheck');
            $name = $this->request->getPost('name');
            $department_id = $this->request->getPost('department_id');

            if (!strlen($mail)) {
                $errors[] = 'メールアドレスを入力してください';
            } else if (!preg_match('/^[0-9a-zA-Z_+\.-]+@[0-9a-zA-Z_\.-]+\.[a-zA-Z]+$/', $mail)) {
                $errors[] = 'メールアドレスを入力してください';
            } else if (!$this->db_manager->get('User')->isUniqueMail($mail)) {
                $errors[] = 'メールアドレスは既に使用されています';
            }
            if (!strlen($password)) {
                $errors[] = 'パスワードを入力してください';
            } else if (4 > strlen($password) || strlen($password) > 30) {
                $errors[] = 'パスワードは4～30文字以内で入力してください';
            }
            if (!strlen($passwordCheck)) {
                $errors[] = 'パスワード再確認を入力してください';
            } else if ($passwordCheck !== $password ) {
                $errors[] = 'パスワードと再確認は一致していません';
            }
            if (!strlen($name))
                $errors[] = '社員名を入力してください';
            
            if(count($errors) === 0) {
                $this->db_manager->get('User')->insert($mail, $password, $name, $department_id);
                $this->session->setAuthenticated(true);
                $user = $this->db_manager->get('User')->fetchByMail($mail);
                $this->session->set('user', $user);    
                $department = $this->db_manager->get('User')->fetchDept($department_id);
                $this->session->set('department', $department); 
            
                //登録完了メール
                $to = $mail;
                $sbj = '【新規登録完了】スケジュール管理';

                $msg = "<hr/>新規登録完了のお知らせ<hr/>";
                $msg .= "この度は、「スケジュール管理」をご利用ありがとうございます。";
                $msg .= "このメールは、ユーザ登録が完了したことをご確認頂くためお送りしているものです。";
                $msg .= "<hr/><br/><ul><li>ユーザ情報</li><br/>ユーザ名：" . $mail;
                $msg .= "<br/>パスワード：" . $password;
                $msg .= "</ul><hr/><br/><ul><li>お知らせ</li><br/>";
                $msg .= "ご不明な点がございましたら気軽にご連絡ください。<br/>";
                $msg .= "また、今後とも宜しくお願い申し上げます。<br/><br/>お問い合わせ先<br/>";
                $msg .= "<a href=\"http://localhost:8080/schedule/\">http://localhost:8080/schedule/</a></ul>";
            
                $hdr = 'Content-Type: text/html;charset=ISO-2022-JP';

                mb_language('ja');
                $sbj = mb_convert_encoding($sbj,'JIS','UTF-8');
                $msg = mb_convert_encoding($msg,'JIS','UTF-8');
                mb_send_mail($to,$sbj,$msg,$hdr);
                //メール設定終了

                return $this->redirect('/');
            }
        }
        return $this->render([
            'mail'          => $mail,
            'password'      => $password,
            'passwordCheck' => $passwordCheck,
            'name'          => $name,
            'depts'         => $depts,
            'errors'        => $errors,
            '_token'        => $this->generateCsrfToken('user/register')
        ], 'register');
    }
    
    public function loginAction() {
        //認証済みならHOME画面へ遷移
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/');
        }
        $mail = '';
        $password = '';
        $errors = [];
        $message = '';

        if (!empty($this->session->get('message'))) {
            $message = $this->session->get('message');
            $this->session->clear();
        }

        if ($this->request->isPost()) {
            $token = $this->request->getPost('_token');
            if (!$this->checkCsrfToken('user/login', $token)) {
                return $this->redirect('/user/login');
            }
            $mail = $this->request->getPost('mail');
            $password = $this->request->getPost('password');

            if (!strlen($mail))
                $errors[] = 'メールアドレスを入力してください';
            if (!strlen($password))
                $errors[] = 'パスワードを入力してください';
            
            if (count($errors) === 0) {
                $user_repository = $this->db_manager->get('User');
                $user = $user_repository->fetchByMail($mail); 
                if (!$user || ($user['password'] !== $user_repository->hashPassword($password))) {
                    $errors[] = 'メールアドレスかパスワードが不正です';
                } else {
                    //認証OKなのでホーム画面遷移
                    $this->session->setAuthenticated(true);
                    $this->session->set('user', $user);
                    $department = $this->db_manager->get('User')->fetchDeptByMail($mail);
                    $this->session->set('department', $department);
                    return $this->redirect('/');
                }
            }
        }
        return $this->render([
            'mail'      => $mail,
            'password'  => $password,
            'errors'    => $errors,
            'message'   => $message,
            '_token'    => $this->generateCsrfToken('user/login')
        ]);
    }

    public function logoutAction() {
        $this->session->clear();
        $this->session->setAuthenticated(false);
        $this->session->set('message','ログアウトしました');
        return $this->redirect('/user/login');
    }
}