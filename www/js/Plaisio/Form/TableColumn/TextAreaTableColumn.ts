import {TextTableColumn} from "../../Table/TableColumn/TextTableColumn";
import {OverviewTable} from "../../Table/OverviewTable";

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
        return OverviewTable.toLowerCaseNoDiacritics(<string>$(tableCell).find('textarea').val());
    }

    //--------------------------------------------------------------------------------------------------------------------
    /**
     * @inheritDoc
     */
    public getSortKey(tableCell): string
    {
        return OverviewTable.toLowerCaseNoDiacritics(<string>$(tableCell).find('textarea').val());
    }

    //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
OverviewTable.registerTableColumn('control-text-area', TextAreaTableColumn);
