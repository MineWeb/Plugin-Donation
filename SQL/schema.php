<?php
class DonationAppSchema extends CakeSchema
{
	public $file = 'schema.php';

	public function before($event = [])	{
		return true;
	}

	public function after($event = [])	{
    }

    public $donation__config = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'objectif' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false],
        'total' => ['type' => 'integer', 'null' => false, 'default' => 0, 'length' => 20, 'unsigned' => false],
        'email' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'unsigned' => false],
        'description' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255, 'unsigned' => false],
        'color' => ['type' => 'string', 'null' => true, 'default' => '#3377ff', 'length' => 255, 'unsigned' => false]
    ];

    public $donation__history = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'payment_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false],
        'user_pseudo' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'unsigned' => false],
        'email' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'unsigned' => false],
        'payment_amount' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false],
        'created' => ['type' => 'datetime', 'null' => false, 'default' => null]
    ];
}