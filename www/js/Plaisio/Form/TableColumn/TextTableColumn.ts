import {Cast} from '../../Helper/Cast';
import {OverviewTable} from '../../Table/OverviewTable';
import {TextTableColumn as BaseTextTableColumn} from '../../Table/TableColumn/TextTableColumn';

/**
 * Table column with cell with input:text form control.
 */
export class TextTableColumn extends BaseTextTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public extractForFilter(tableCell: HTMLTableCellElement): string
  {
    return OverviewTable.toLowerCaseNoDiacritics(Cast.toManString($(tableCell).find('input').val(), ''));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public getSortKey(tableCell: HTMLTableCellElement): string
  {
    return OverviewTable.toLowerCaseNoDiacritics(Cast.toManString($(tableCell).find('input').val(), ''));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
OverviewTable.registerTableColumn('control-text', TextTableColumn);
OverviewTable.registerTableColumn('control-button', TextTableColumn);
OverviewTable.registerTableColumn('control-submit', TextTableColumn);
