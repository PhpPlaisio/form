import {TextTableColumn} from "../../Table/TableColumn/TextTableColumn";
import {OverviewTable} from "../../Table/OverviewTable";

/**
 * Table column with cells with select boxes.
 */
export class SelectTableColumn extends TextTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public extractForFilter(tableCell: HTMLTableCellElement): string
  {
    const text = $(tableCell).find('select option:selected').text();

    return OverviewTable.toLowerCaseNoDiacritics(text);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public getSortKey(tableCell: HTMLTableCellElement): string
  {
    const text = $(tableCell).find('select option:selected').text();

    return OverviewTable.toLowerCaseNoDiacritics(text);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
OverviewTable.registerTableColumn('control-select', SelectTableColumn);
