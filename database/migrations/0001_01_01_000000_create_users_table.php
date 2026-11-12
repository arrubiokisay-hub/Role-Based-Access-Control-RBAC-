public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('username')->unique();
        $table->string('name');
        $table->string('email')->nullable(); // ✅ Add this line
        $table->string('password');
        $table->string('role');
        $table->timestamps();
    });
}