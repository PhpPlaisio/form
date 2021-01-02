import * as Cookies from 'js-cookie';
import {Cast} from '../Helper/Cast';

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
   * The jQuery object of the form.
   */
  private $form: JQuery;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param $form The jQuery object of the form.
   */
  public constructor($form: JQuery)
  {
    this.$form = $form;

    // Install event handlers.
    const that = this;
    this.$form.on('submit', function ()
    {
      that.$form.find(':disabled').prop('disabled', false);
    });

    this.$form.on('submit', Form.setCsrfValue);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Registers form that matches a jQuery selector as a Form.
   *
   * @param selector The jQuery selector.
   */
  public static registerForm(selector: string): void
  {
    const that = this;

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
  /**
   * Sets the CSRF value.
   */
  private static setCsrfValue(event: JQuery.TriggeredEvent): void
  {
    const $input    = $(event.target).find('input[type=hidden][name=ses_csrf_token]');
    const csrfToken = Cast.toManString(Cookies.get('ses_csrf_token'), '');
    $input.val(csrfToken);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
// Plaisio\Console\Helper\TypeScript\TypeScriptMarkHelper::md5: aa3a1c176faa77acfb929d2d1f0167b4
