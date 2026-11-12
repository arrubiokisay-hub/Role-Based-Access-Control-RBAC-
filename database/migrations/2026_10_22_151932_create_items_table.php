public function up()
{
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->enum('type', ['lost','found']);
        $table->enum('status', ['Pending','Approved','Rejected','Claimed'])->default('Pending');
        $table->string('location')->nullable();
        $table->string('category')->nullable();
        $table->string('status');
        $table->string('image')->nullable();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}