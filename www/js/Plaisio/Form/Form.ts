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
    const $body = $('body');
    $body.on(Kernel.eventTypeBeefyHtmlAdded, function (event: TriggeredEvent, html: HTMLElement)
    {
      $(html).find('form').each(function ()
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
// Plaisio\Console\Helper\TypeScript\TypeScriptMarkHelper::md5: beca2bccf50a3093b4ac97f05240c4f5
