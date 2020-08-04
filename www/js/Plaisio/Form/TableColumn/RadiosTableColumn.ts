import {TextTableColumn} from "../../Table/TableColumn/TextTableColumn";
import {OverviewTable} from "../../Table/OverviewTable";

/**
 * Table column with cells with radio buttons.
 */
export class RadiosTableColumn extends TextTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public extractForFilter(tableCell: HTMLTableCellElement): string
  {
    const id = $(tableCell).find('input[type="radio"]:checked').prop('id');

    return OverviewTable.toLowerCaseNoDiacritics(($('label[for=' + $.escapeSelector(id) + ']').text()));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public getSortKey(tableCell:HTMLTableCellElement): string
  {
    const id = $(tableCell).find('input[type="radio"]:checked').prop('id');

    return OverviewTable.toLowerCaseNoDiacritics(($('label[for=' + $.escapeSelector(id) + ']').text()));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
OverviewTable.registerTableColumn('control-radios', RadiosTableColumn);
