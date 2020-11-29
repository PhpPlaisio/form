import {TextTableColumn} from "../../Table/TableColumn/TextTableColumn";
import {OverviewTable} from "../../Table/OverviewTable";

/**
 * Table column with HTML elements.
 */
export class HtmlTableColumn extends TextTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public extractForFilter(tableCell: HTMLTableCellElement): string
  {
    return OverviewTable.toLowerCaseNoDiacritics($(tableCell).children().text());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public getSortKey(tableCell: HTMLTableCellElement): string
  {
    return OverviewTable.toLowerCaseNoDiacritics($(tableCell).children().text());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
OverviewTable.registerTableColumn('control-html', HtmlTableColumn);
