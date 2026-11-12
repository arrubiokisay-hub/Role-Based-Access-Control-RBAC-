Project title and short description:
"Lost & Found Web Portal / Barangay Management System"

A web-based application designed to manage and track lost and found items within the community, featuring secure session-based authentication and Role-Based Access Control (RBAC) to separate administrator and resident user capabilities.

Selected Approach:
Dynamic Middleware Approach: Utilized a parameterized custom middleware (CheckRole) that accepts dynamic role arguments (...$roles) to evaluate session permissions across multiple route groups cleanly.

Role-and-Permission Matrix

| Feature / Route / Action | Admin | Regular User | Guest (Logged Out) |
| :--- | :---: | :---: | :---: |
| **View Public Portal / Home** (`/`) | Allowed | Allowed | Allowed |
| **View User Dashboard** (`/dashboard`) | Allowed | Allowed | Blocked (Redirect to Login) |
| **Report Lost / Found Items** | Allowed | Allowed | Blocked (Redirect to Login) |
| **Access Admin Dashboard** (`/admin/dashboard`) | Allowed | Denied (403) | Blocked (Redirect to Login) |
| **Manage / Delete Records** (`/admin/items/{id}`) | Allowed | Denied (403) | Blocked (Redirect to Login) |



Middleware Configuration & Protected Routes:

Middleware Class: App\Http\Middleware\CheckRole (app/Http/Middleware/CheckRole.php)

Route Alias: Registered as 'role' inside bootstrap/app.php.

Protected Routes:

Admin-Only Group (role:admin): Protects /admin/dashboard, status updates, delete actions, and history log views.

User & Shared Group (role:admin,user): Protects /dashboard, item reporting endpoints, and individual item management views.



How the Middleware Authorizes Requests?

The middleware class CheckRole checks the incoming HTTP request to check the session keys of the user (session('username') and session('role')). In case there are no such session keys set for the guest, he will be redirected safely to the login screen. In case the user has signed in, the middleware will check whether the role set in the session corresponds to the allowed roles set in the route function parameters. In case the user does not have enough permissions, then the process stops with an abort(403) call.



What was challenging, and why is server-side authorization necessary?

Working with session-based roles proved highly educational in understanding how security works behind the scenes on web applications, especially in regards to working with redirect loops and routes caching. The hardest part turned out to be configuring Laravel middleware routing with custom session drivers in order to ensure interception of unauthentic users and to avoid any exceptions from the framework. It has shown once again how crucial it is to work with server authorization, as depending only on the interface elements such as hiding buttons gives absolutely no protection, as users can simply enter URLs themselves.