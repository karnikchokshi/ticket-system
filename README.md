# ticket-system

==> After clone dev-addweb branch : 
    --git clone -b dev-addweb https://github.com/karnikchokshi/ticket-system.git

==> run below command

    1) composer install
    2) npm install
    3) npm run dev
    4) php artisan migrate:fresh --seed

-- Refer RolesAndPermissionsSeeder.php for roles and permissions details with user data.
-- only editor can update ticket.
-- viewer user only view ticket data.

-- Email notification is not working if smtp is not configured.(send email code is commented.)
