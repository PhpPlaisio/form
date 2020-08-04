import {Cast} from '../../Helper/Cast';
import {OverviewTable} from '../../Table/OverviewTable';
import {TextTableColumn} from '../../Table/TableColumn/TextTableColumn';

/**
 * Table column with cells with a text area.
 */
export class TextAreaTableColumn extends TextTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public extractForFilter(tableCell: HTMLTableCellElement): string
  {
    return OverviewTable.toLowerCaseNoDiacritics(Cast.toManString($(tableCell).find('textarea').val(), ''));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public getSortKey(tableCell: HTMLTableCellElement): string
  {
    return OverviewTable.toLowerCaseNoDiacritics(Cast.toManString($(tableCell).find('textarea').val(), ''));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
OverviewTable.registerTableColumn('control-text-area', TextAreaTableColumn);
