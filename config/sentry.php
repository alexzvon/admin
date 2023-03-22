<?php

return array(
    'dsn' => 'https://19e61dc2684441649eb4177785b7d7ba@sentry.io/1521695',

    'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD')),

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,
);
