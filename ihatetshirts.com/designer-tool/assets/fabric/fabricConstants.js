'use strict';
angular.module('common.fabric.constants', []).service('FabricConstants', [function() {

	var objectDefaults = {
		rotatingPointOffset: 10,
		padding: 10,
		borderColor: 'EEF6FC',
		cornerColor: '#FFC23F',
		cornerSize: 24,
		transparentCorners: false,
		hasRotatingPoint: false,
		centerTransform: true
	};

	return {

		JSONExportProperties: [
			'height',
			'width',
			'background',
			'objects',

			'originalHeight',
			'originalWidth',
			'originalScaleX',
			'originalScaleY',
			'originalLeft',
			'originalTop',

			'lineHeight',
			'lockMovementX',
			'lockMovementY',
			'lockScalingX',
			'lockScalingY',
			'lockUniScaling',
			'lockRotation',
			'lockObject',
			'id',
			'isTinted',
			'filters'
		],

        imageFilters: [
            'grayscale',
            'invert',
            'remove-white',
            'sepia',
            'sepia2',
            'brightness',
            'noise',
            'gradient-transparency',
            'pixelate',
            'blur',
            'sharpen',
            'emboss',
            'tint',
            'multiply',
            'blend'
        ],

		shapeDefaults: angular.extend({
            left: 150,
            top:200,
            scaleX: .35,
            scaleY:.35
		}, objectDefaults),

        imageDefaults: angular.extend({

        }, objectDefaults),

		textDefaults: angular.extend({
			originX: 'left',
			scaleX: 1,
			scaleY: 1,
			fontFamily: 'Adamina',
			fontSize: 32,
			fill: '#000000',
			textAlign: 'left'
		}, objectDefaults),

        curvedTextDefaults: angular.extend({
            angle: 0,
            spacing: 10,
            radius: 100,
            text: 'Curved text',
            textAlign: 'center',
            reverse: false
        }, objectDefaults)

	};

}]);
