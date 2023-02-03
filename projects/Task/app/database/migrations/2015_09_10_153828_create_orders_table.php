<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


use App\Models\OrderStatusConstants as Status;
use App\Models\OrderDeliveryConstants as Delivery;
use App\Models\OrderPaymentConstants as Payment;
use App\Models\OrderTypeConstants as Type;

class CreateOrdersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$allowedStatuses = [
			Status::NOVEL,
			Status::PROCESSED,
			Status::CANCELLED,
			Status::EXECUTED,
			Status::RETURNS,
			Status::REFUSAL,
		];

		$allowedDeliveries = [
			Delivery::OWN_EXPENSE,
			Delivery::ADDRESS,
			Delivery::COURIER,
		];

		$allowedPayments = [
			Payment::CASH,
			Payment::CASHLESS,
			Payment::INVOICING_FOR_LEGAL_ENTITY,
			Payment::INVOICING_FOR_PHYSICAL_PERSON
		];

		$allowedTypes = [
			Type::FROM_SITE,
			Type::FAST,
			Type::INCOMPLETE,
		];

		Schema::create(
			'orders',
			function (Blueprint $table) use ($allowedStatuses, $allowedDeliveries, $allowedPayments, $allowedTypes) {
				$table->increments('id');
				$table->timestamps();

				$table->string('name')->default('');
				$table->string('phone')->default('');
				$table->string('email')->default('');
				$table->text('comment')->default('');

				$table->enum('order_status', $allowedStatuses)->default($allowedStatuses[0]);
				$table->enum('order_delivery', $allowedDeliveries)->default($allowedDeliveries[0]);
				$table->enum('order_payment', $allowedPayments)->default($allowedPayments[0]);
				$table->enum('order_type', $allowedTypes)->default($allowedTypes[0]);

				$table->string('created_hash');
			}
		);

		DB::update("ALTER TABLE orders AUTO_INCREMENT = 301;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}
}
