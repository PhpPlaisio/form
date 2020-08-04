import {TextTableColumn} from "../../Table/TableColumn/TextTableColumn";
import {OverviewTable} from "../../Table/OverviewTable";

/**
 * Table column with with cells with a radio button.
 */
export class RadioTableColumn extends TextTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public extractForFilter(tableCell: HTMLTableCellElement): string
  {
    if ($(tableCell).find('input:radio').prop('checked'))
    {
      return '1';
    }

    return '0';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public getSortKey(tableCell: HTMLTableCellElement): string
  {
    if ($(tableCell).find('input:radio').prop('checked'))
    {
      return '1';
    }

    return '0';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
OverviewTable.registerTableColumn('control-radio', RadioTableColumn);
