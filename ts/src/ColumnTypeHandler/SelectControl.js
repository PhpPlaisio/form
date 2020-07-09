/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/SelectControl',

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
    function SelectControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    SelectControl.prototype = Object.create(Text.prototype);
    SelectControl.constructor = SelectControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the label of the checked radio button.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns string
     */
    SelectControl.prototype.extractForFilter = function (tableCell) {
      var text;

      text = $(tableCell).find('select option:selected').text();

      return OverviewTable.toLowerCaseNoDiacritics(text);
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the label of the checked radio button.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns string
     */
    SelectControl.prototype.getSortKey = function (tableCell) {
      var text;

      text = $(tableCell).find('select option:selected').text();

      return OverviewTable.toLowerCaseNoDiacritics(text);
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-select', SelectControl);

    //------------------------------------------------------------------------------------------------------------------
    return SelectControl;
  }
);

//------------------------------------------------------------------------------------------------------------------


