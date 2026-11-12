public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('username')->unique();
        $table->string('role')->default('user');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['username', 'role']);
    });
}