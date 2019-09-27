<?php declare( strict_types=1 );
/**
 * User: Alexander
 * Date: 9/27/19
 * Time: 01:06
 */

namespace Clarabridge\ApiTest;

/**
 * A class to represent canned responses.
 *
 * Class CannedReponse
 * @package Clarabridge\ApiTest
 */
class CannedReponse {

	// The variables for our object.
	private $id;
	private $name;
	private $message;
	private $type;
	private $labels;
	private $images;
	private $folder;
	private $data;

	/**
	 * CannedReponse constructor.
	 *
	 * @param $id
	 * @param $name
	 * @param $message
	 * @param $type
	 * @param $labels
	 * @param $images
	 * @param $folder
	 * @param $data
	 */
	public function __construct( $id, $name, $message, $type, $labels = [], $images = [], $folder = null, $data = null ) {
		$this->id      = $id;
		$this->name    = $name;
		$this->message = $message;
		$this->type    = $type;
		$this->labels  = $labels;
		$this->images  = $images;
		$this->folder  = $folder;
		$this->data    = $data;
	}

	/**
	 * @return mixed
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function set_id( $id ): void {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function set_name( $name ): void {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * @param mixed $message
	 */
	public function set_message( $message ): void {
		$this->message = $message;
	}

	/**
	 * @return mixed
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param mixed $type
	 */
	public function set_type( $type ): void {
		$this->type = $type;
	}

	/**
	 * @return array
	 */
	public function get_labels(): array {
		return $this->labels;
	}

	/**
	 * @param array $labels
	 */
	public function set_labels( array $labels ): void {
		$this->labels = $labels;
	}

	/**
	 * @return array
	 */
	public function get_images(): array {
		return $this->images;
	}

	/**
	 * @param array $images
	 */
	public function set_images( array $images ): void {
		$this->images = $images;
	}

	/**
	 * @return null
	 */
	public function get_folder() {
		return $this->folder;
	}

	/**
	 * @param null $folder
	 */
	public function set_folder( $folder ): void {
		$this->folder = $folder;
	}

	/**
	 * @return null
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * @param null $data
	 */
	public function set_data( $data ): void {
		$this->data = $data;
	}


}