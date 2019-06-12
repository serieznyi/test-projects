package org.intech.serieznyi.forum.controller;

import org.intech.serieznyi.forum.domain.entity.Message;
import org.intech.serieznyi.forum.domain.entity.Theme;
import org.intech.serieznyi.forum.domain.repository.MessageRepository;
import org.intech.serieznyi.forum.domain.repository.ThemeRepository;
import org.intech.serieznyi.forum.dto.MessageCreateDto;
import org.intech.serieznyi.forum.service.MessageService;
import org.intech.serieznyi.forum.util.FlashMessage;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Controller;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import javax.servlet.http.HttpServletRequest;
import javax.validation.Valid;
import java.util.Optional;
import java.util.UUID;

@Controller
public class MessageController {
    private ThemeRepository themeRepository;
    private FlashMessage flashMessage;
    private MessageService messageService;
    private MessageRepository messageRepository;

    MessageController(
            ThemeRepository themeRepository,
            FlashMessage flashMessage,
            MessageService messageService,
            MessageRepository messageRepository
    ) {
        this.themeRepository = themeRepository;
        this.flashMessage = flashMessage;
        this.messageService = messageService;
        this.messageRepository = messageRepository;
    }

    @PostMapping(value = "/theme/{themeId}/message/create")
    public String createSubmit(
            @Valid @ModelAttribute("form") Message message,
            @PathVariable String themeId,
            BindingResult bindingResult,
            RedirectAttributes redirectAttributes
    ) {
        Optional<Theme> themeOptional = themeRepository.findById(UUID.fromString(themeId));

        if (!themeOptional.isPresent()) {
            flashMessage.addErrorMessage(redirectAttributes, "theme_not_found");
            return "redirect:/theme/" + themeId;
        } else if (bindingResult.hasErrors()) {
            flashMessage.addErrorMessage(redirectAttributes, bindingResult.getAllErrors().toString());
            return "redirect:/theme/" + themeId;
        } else {
            message.setTheme(themeOptional.get());
            messageService.createNew(message);
        }

        return "redirect:/theme/" + themeId;
    }

    @PostMapping("/message/delete/{id}")
    public String deleteSubmit(
            @PathVariable String id,
            RedirectAttributes redirectAttributes,
            HttpServletRequest request
    )
    {
        messageRepository.findById(UUID.fromString(id)).ifPresent(message -> messageService.delete(message));

        flashMessage.addSuccessMessage(redirectAttributes, "flash.entity.successful_deleted");

        String refererUrl = request.getHeader("referer");

        return "redirect:" + (refererUrl != null && !refererUrl.isEmpty() ? refererUrl : "/");
    }
}
