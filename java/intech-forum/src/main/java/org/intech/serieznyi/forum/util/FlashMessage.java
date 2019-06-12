package org.intech.serieznyi.forum.util;

import org.springframework.context.MessageSource;
import org.springframework.stereotype.Component;
import org.springframework.ui.Model;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.Locale;

@Component
public class FlashMessage {
    private static final String CONTENT_KEY = "flash_message_content";
    private static final String TYPE_KEY = "flash_message_type";
    private Model model;

    public enum Type {
        DANGER,
        SUCCESS
    }

    private final MessageSource messageSource;
    private final Locale locale;

    public FlashMessage(MessageSource messageSource, Locale locale) {
        this.messageSource = messageSource;
        this.locale = locale;
    }

    private void addMessage(RedirectAttributes redirectAttributes, Type type, String message) {
        if (this.model != null) {
            model.addAttribute(FlashMessage.TYPE_KEY, type.toString().toLowerCase());
            model.addAttribute(FlashMessage.CONTENT_KEY, message);
        }

        redirectAttributes.addFlashAttribute(TYPE_KEY, type.toString().toLowerCase());
        redirectAttributes.addFlashAttribute(
                CONTENT_KEY,
                messageSource.getMessage(message, new String[]{}, locale)
        );
    }

    public void addSuccessMessage(RedirectAttributes redirectAttributes, String message) {
        addMessage(redirectAttributes, Type.SUCCESS, message);
    }

    public void addErrorMessage(RedirectAttributes redirectAttributes, String message) {
        addMessage(redirectAttributes, Type.DANGER, message);
    }

    public void setModel(Model model) {
        this.model = model;
    }
}
