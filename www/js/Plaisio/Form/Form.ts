import * as $ from 'jquery';
import * as Cookies from 'js-cookie';
import {Cast} from 'Plaisio/Helper/Cast';
import {Kernel} from 'Plaisio/Kernel/Kernel';
import TriggeredEvent = JQuery.TriggeredEvent;

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

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param $form The jQuery object of the form.
   */
  public constructor(private $form: JQuery<Element>)
  {
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
   * Registers form as a Form.
   */
  public static init(): void
  {
    Kernel.onBeefyHtmlAdded(function (event: TriggeredEvent, $html: JQuery)
    {
      $html.find('form').each(function ()
      {
        const $form = $(this);
        if (!$form.hasClass('is-registered'))
        {
          Form.forms.push(new Form($form));
          $form.addClass('is-registered');
        }
      });
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
// Plaisio\Console\Helper\TypeScript\TypeScriptMarkHelper::md5: a39a096a37bff53d222f579cbcf1990d
