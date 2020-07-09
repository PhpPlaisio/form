/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/RadioControl',

  ['jquery',
    'SetBased/Abc/Table/OverviewTable',
    'SetBased/Abc/Table/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a text input form control.
     */
    function RadioControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    RadioControl.prototype = Object.create(Text.prototype);
    RadioControl.constructor = RadioControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of the input box in a tableCell.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns string
     */
    RadioControl.prototype.extractForFilter = function (tableCell) {
      if ($(tableCell).find('input:radio').prop('checked')) {
        return '1';
      }

      return '0';
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of a table cell.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns {string}
     */
    RadioControl.prototype.getSortKey = function (tableCell) {
      if ($(tableCell).find('input:radio').prop('checked')) {
        return '1';
      }

      return '0';
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-radio', RadioControl);

    //------------------------------------------------------------------------------------------------------------------
    return RadioControl;
  }
);

//------------------------------------------------------------------------------------------------------------------
