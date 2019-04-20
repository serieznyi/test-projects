package com.moscoding.practice6;

import org.springframework.context.MessageSource;
import org.springframework.stereotype.Component;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.Locale;

@Component
public class FlashMessageHelper {
    public enum Type {
        ERROR,
        SUCCESS
    }

    private final MessageSource messageSource;
    private final Locale locale;

    public FlashMessageHelper(MessageSource messageSource, Locale locale) {
        this.messageSource = messageSource;
        this.locale = locale;
    }

    private void addMessage(RedirectAttributes redirectAttributes, Type type, String message) {
        redirectAttributes.addFlashAttribute("flash_message_type", type.toString().toLowerCase());
        redirectAttributes.addFlashAttribute(
                "flash_message_content",
                messageSource.getMessage(message, new String[]{}, locale)
        );
    }

    public void addSuccessMessage(RedirectAttributes redirectAttributes, String message) {
        addMessage(redirectAttributes, Type.SUCCESS, message);
    }

    public void addErrorMessage(RedirectAttributes redirectAttributes, String message) {
        addMessage(redirectAttributes, Type.ERROR, message);
    }
}
