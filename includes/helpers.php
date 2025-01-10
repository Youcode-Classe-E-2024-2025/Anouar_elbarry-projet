<?php
function sanitize_project_id($id)
 {
     return htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
     }