/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/RadiosControl',

  ['jquery',
    'SetBased/Abc/Table/OverviewTable',
    'SetBased/Abc/Table/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a text input form control.
     *
     * @constructor
     */
    function RadiosControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    RadiosControl.prototype = Object.create(Text.prototype);
    RadiosControl.constructor = RadiosControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the label of the checked radio button.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns string
     */
    RadiosControl.prototype.extractForFilter = function (tableCell) {
      var id;

      id = $(tableCell).find('input[type="radio"]:checked').prop('id');

      return OverviewTable.toLowerCaseNoDiacritics(($('label[for=' + id + ']').text()));
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the label of the checked radio button.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns string
     */
    RadiosControl.prototype.getSortKey = function (tableCell) {
      var id;

      id = $(tableCell).find('input[type="radio"]:checked').prop('id');

      return OverviewTable.toLowerCaseNoDiacritics(($('label[for=' + id + ']').text()));
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-radios', RadiosControl);

    //------------------------------------------------------------------------------------------------------------------
    return RadiosControl;
  }
);

//----------------------------------------------------------------------------------------------------------------------
