<?php 
/**
 *
 * visualizzo servizi associati all'hotel:
 * @parameters: servizi (array servizi in lingua corrispondenti)
 *
 *
 */
 ?>


@if (count($servizi))
  <p>{{implode(',', $servizi). ', ....'}}</p>
@endif
