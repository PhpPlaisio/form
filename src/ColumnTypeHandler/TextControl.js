/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/TextControl',

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
    function TextControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    TextControl.prototype = Object.create(Text.prototype);
    TextControl.constructor = TextControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of the input box in a tableCell.
     *
     * @param {HTMLTableElement} tableCell The table cell.
     *
     * @returns string
     */
    TextControl.prototype.extractForFilter = function (tableCell) {
      return OverviewTable.toLowerCaseNoDiacritics($(tableCell).find('input').val());
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of a table cell.
     *
     * @param {HTMLTableCellElement} tableCell The table cell.
     *
     * @returns {string}
     */
    TextControl.prototype.getSortKey = function (tableCell) {
      return OverviewTable.toLowerCaseNoDiacritics($(tableCell).find('input').val());
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-text', TextControl);
    OverviewTable.registerColumnTypeHandler('control-button', TextControl);
    OverviewTable.registerColumnTypeHandler('control-submit', TextControl);

    //------------------------------------------------------------------------------------------------------------------
    return TextControl;
  }
);

//------------------------------------------------------------------------------------------------------------------
