/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/TextAreaControl',

  ['jquery',
    'SetBased/Abc/Table/OverviewTable',
    'SetBased/Abc/Table/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a textarea form control.
     *
     * @constructor
     */
    function TextAreaControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    TextAreaControl.prototype = Object.create(Text.prototype);
    TextAreaControl.constructor = TextAreaControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of the input box in a tableCell.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns string
     */
    TextAreaControl.prototype.extractForFilter = function (tableCell) {
      return OverviewTable.toLowerCaseNoDiacritics($(tableCell).find('textarea').val());
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of a table cell.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns {string}
     */
    TextAreaControl.prototype.getSortKey = function (tableCell) {
      return OverviewTable.toLowerCaseNoDiacritics($(tableCell).find('textarea').val());
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handler.
     */
    OverviewTable.registerColumnTypeHandler('control-text-area', TextAreaControl);

    //------------------------------------------------------------------------------------------------------------------
    return TextAreaControl;
  }
);

//----------------------------------------------------------------------------------------------------------------------
