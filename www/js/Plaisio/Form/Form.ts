/**
 * Class for forms.
 */
export class Form
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * All registered forms.
   */
  protected static forms: Form[] = [];

  /**
   * The jQuery object of this form.
   */
  private $from: JQuery;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param $form The jQuery object of the form.
   */
  public constructor($form)
  {
    let that = this;

    this.$from = $form;

    // Install event handlers.
    this.$from.on('submit', function ()
    {
      that.$from.find(':disabled').prop('disabled', false);
    });
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Registers form that matches a jQuery selector as a Form.
   *
   * @param selector The jQuery selector.
   */
  public static registerForm(selector: string): void
  {
    let that = this;

    $(selector).each(function ()
    {
      let $form = $(this);

      if (!$form.is('form'))
      {
        $form = $form.find('form').first();
      }

      if (!$form.hasClass('is-registered'))
      {
        Form.forms.push(new that($form));
        $form.addClass('is-registered');
      }
    });
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
