<?php
namespace Drupal\helloworld\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Render\Markup;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {
	/**
	   * Liste des groupes.
	   *
	   * @return array
	   *   Return Hello string.
	*/
	public function hello(){	
		$connection = Database::getConnection();
		
		$options = array();
		$result = $connection->query('SELECT * FROM listofbands', $options);
			
		$headers = array
		(
			'groupe' => t('<center><b>Groupe ou artiste</b></center>'),
			'style' => t('<center><b>Style</b></center>'),
		);		
					
		foreach($result as $item) {
			$data[] = array(
				$band = $item->band_name,		
				$style = $item->style_rock,					
			);
		}
		
		return array(		
		  '#theme' => 'table',
		  '#header' => $headers,
		  '#rows' => $data,
		);
	}
}
