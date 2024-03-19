<?php

#-Способность питомца в зависимости от ID-#
function pets_ability($pets_id, $abi_prosent){
if($pets_id == 1){$ability = "<img src='/style/images/pets/ability/thief.gif' alt=''/><span class='yellow'>Везение:</span> Шанс срабатывания $abi_prosent%";}
if($pets_id == 2){$ability = "<img src='/style/images/pets/ability/absorb.gif' alt=''/><span class='yellow'>Поглощение:</span> Шанс срабатывания $abi_prosent%";}	
if($pets_id == 3){$ability = "<img src='/style/images/pets/ability/crete.gif' alt=''/><span class='yellow'>Крит:</span> Шанс срабатывания $abi_prosent%";}	
if($pets_id == 4){$ability = "<img src='/style/images/pets/ability/dodge.gif' alt=''/><span class='yellow'>Уворот:</span> Шанс срабатывания $abi_prosent%";}
if($pets_id == 5){$ability = "<img src='/style/images/pets/ability/treatment.gif' alt=''/><span class='yellow'>Вампиризм:</span> Шанс срабатывания $abi_prosent%";}	
return $ability;
}
?>