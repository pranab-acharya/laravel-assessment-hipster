<div>
    <x-admin-nav />

    <div class="mx-auto p-6 bg-gray-50 min-h-screen space-y-6">
        <x-notify />

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Product Stats -->
            <div class="bg-white shadow-md rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Stats</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-stat-card label="Total Products" value="{{ $totalProducts }}" color="blue" />
                    <x-stat-card label="Total Orders" value="{{ $totalOrders }}" color="green" />
                    <x-stat-card label="Pending Orders" value="{{ $totalPendingOrders }}" color="orange" />
                </div>
            </div>

            <!-- User Stats -->
            <div class="bg-white shadow-md rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">User Stats</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-stat-card label="Total Customers" value="{{ $totalCustomers }}" color="purple" />
                    <x-stat-card label="Total Admins" value="{{ $totalAdmins }}" color="indigo" />
                    <x-stat-card label="Online Customers" value="{{ $totalOnlineCustomers }}" color="emerald" />
                </div>
            </div>
        </div>

        <!-- Online Users -->
        <div class="bg-white shadow-md rounded-2xl p-6" wire:poll.30s="loadOnlineUsers">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Online Users</h3>

            <!-- Summary -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <x-summary-card label="Online Customers" value="{{ $totalOnlineCustomers }}" color="green" />
                <x-summary-card label="Online Admins" value="{{ $totalOnlineAdmins }}" color="blue" />
            </div>

            <!-- Lists -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customers -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-3">Customers</h4>
                    <x-online-user-list :users="$onlineUsers" type="customer" />
                </div>

                <!-- Admins -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-3">Admins</h4>
                    <x-online-user-list :users="$onlineAdmins" type="admin" />
                </div>
            </div>
        </div>
    </div>
</div>
