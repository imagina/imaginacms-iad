<?php

//Options to age
$optionsAge = [];
for ($i = 18; $i <= 60; $i++) $optionsAge[] = ['label' => $i, 'value' => $i];

return [
  //Extra field to crud ads
  'ads' => [
    'name' => [
      'value' => null,
      'name' => 'name',
      'type' => 'input',
      'required' => true,
      'fakeFieldName' => 'fields',
      'props' => [
        'label' => 'Nombre*',
      ]
    ],
    'age' => [
      'value' => 18,
      'name' => 'age',
      'type' => 'select',
      'fakeFieldName' => 'fields',
      'props' => [
        'label' => 'Edad',
        'options' => $optionsAge
      ]
    ],
    'linkExperiences' => [
      'value' => [],
      'name' => 'linkExperiences',
      'type' => 'select',
      'fakeFieldName' => 'options',
      'props' => [
        'label' => 'Experiencias de tus clientes',
        'hint' => 'Si tienes experiencias o reseñas publicadas por catadores en foros de prepagos como ForoPrepagosColombia, DonColombia, etc. y quieres que las enlacemos desde tu anuncio, indica las direcciones web',
        'useInput' => true,
        'useChips' => true,
        'multiple' => true,
        'hideDropdownIcon' => true,
        'newValueMode' => 'add-unique',
        'clearable' => true
      ]
    ],
  ]
];
