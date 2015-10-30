<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->polygons();
		/*$this->redirect(
				Yii::app()->createUrl("preProcessing/PreProcessing")
				);
			*/	
		
	}
	
	function polygons(){
    
    
    $handle = fopen ("php://stdin","r");
    $t = fgets($handle);
    
    print($t);
    
    
}
	
	/*function staircase($height = 6){
    
    for($i = 0; $i <$height; $i++){
        for($j = 0; $j < $height; $j++){
            if($j < $height - ($i + 1))
                print(" ");
            else
                print("#");
        }
		print("\n");
    }
    
}*/
	
}