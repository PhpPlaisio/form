/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/CheckboxControl',

  ['jquery',
    'SetBased/Abc/Table/OverviewTable',
    'SetBased/Abc/Table/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a text input form control.
     */
    function CheckboxControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    CheckboxControl.prototype = Object.create(Text.prototype);
    CheckboxControl.constructor = CheckboxControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of the input box in a tableCell.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns string
     */
    CheckboxControl.prototype.extractForFilter = function (tableCell) {
      if ($(tableCell).find('input:checkbox').prop('checked')) {
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
    CheckboxControl.prototype.getSortKey = function (tableCell) {
      if ($(tableCell).find('input:checkbox').prop('checked')) {
        return '1';
      }

      return '0';
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-checkbox', CheckboxControl);

    //------------------------------------------------------------------------------------------------------------------
    return CheckboxControl;
  }
);

//------------------------------------------------------------------------------------------------------------------
