<?php
function sanitize_project_id($id)
 {
     return htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
     }

     function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }