package org.intech.serieznyi.forum.controller;

import org.intech.serieznyi.forum.domain.entity.Message;
import org.intech.serieznyi.forum.domain.entity.Theme;
import org.intech.serieznyi.forum.domain.repository.MessageRepository;
import org.intech.serieznyi.forum.domain.repository.ThemeRepository;
import org.intech.serieznyi.forum.dto.MessageCreateDto;
import org.intech.serieznyi.forum.service.FieldValidationException;
import org.intech.serieznyi.forum.service.ThemeService;
import org.intech.serieznyi.forum.util.FlashMessage;
import org.intech.serieznyi.forum.util.Translator;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Sort;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.FieldError;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import javax.validation.Valid;
import java.util.Optional;
import java.util.UUID;

import static org.intech.serieznyi.forum.util.PageUtil.PAGE_SIZE;

@Controller
public class ThemeController {
    private ThemeService themeService;
    private ThemeRepository themeRepository;
    private Translator translator;
    private FlashMessage flashMessage;
    private MessageRepository messageRepository;

    ThemeController(
            ThemeService themeService,
            ThemeRepository themeRepository,
            Translator translator,
            FlashMessage flashMessage,
            MessageRepository messageRepository
    ) {
        this.themeService = themeService;
        this.themeRepository = themeRepository;
        this.translator = translator;
        this.flashMessage = flashMessage;
        this.messageRepository = messageRepository;
    }

    @GetMapping("/")
    public String indexShow(Model model, @RequestParam("page") Optional<Integer> pageNumber) {

        int currentPage = pageNumber.orElse(1);

        Page<Theme> page = themeRepository.findAll(
                PageRequest.of(
                        currentPage - 1,
                        PAGE_SIZE,
                        new Sort(Sort.Direction.DESC, "lastActivityDate")
                )
        );

        model.addAttribute("page", page);

        return "page/theme/list";
    }

    @GetMapping("/theme/create")
    @PreAuthorize("hasPermission(#theme, 'create')")
    public String createShow(@ModelAttribute("form") Theme theme) {
        return "page/theme/create";
    }

    @PostMapping("/theme/create")
    @PreAuthorize("hasPermission(#theme, 'create')")
    public String createSubmit(
            @Valid @ModelAttribute("form") Theme theme,
            BindingResult bindingResult
    ) {

        if (bindingResult.hasErrors()) {
            return "page/theme/create";
        }

        try {
            return "redirect:/theme/" + themeService.create(theme).getId();
        } catch (FieldValidationException e) {
            bindingResult.addError(new FieldError(
                    "themeForm",
                    e.getFieldName(),
                    translator.translate(e.getMessage())
            ));

            return "page/theme/create";
        }
    }

    @GetMapping("/theme/{id}")
    public String themeShow(
            Model model,
            @PathVariable String id,
            @ModelAttribute("form") Theme form,
            @RequestParam("page") Optional<Integer> pageNumber
    ) {
        Optional<Theme> themeOptional = themeRepository.findById(UUID.fromString(id));

        if (!themeOptional.isPresent()) {
            return "404";
        }

        int currentPage = pageNumber.orElse(1);

        Page<Message> messagesPage = messageRepository.findByTheme(
                themeOptional.get(),
                PageRequest.of(
                        currentPage - 1,
                        PAGE_SIZE,
                        new Sort(Sort.Direction.ASC, "createdDate")
                )
        );

        model.addAttribute("theme", themeOptional.get());
        model.addAttribute("form", new MessageCreateDto());
        model.addAttribute("page", messagesPage);

        return "page/theme/view";
    }

    @PostMapping("/theme/delete/{id}")
    public String deleteSubmit(@PathVariable String id, RedirectAttributes redirectAttributes) {
        themeRepository.findById(UUID.fromString(id)).ifPresent(theme -> themeService.delete(theme));

        flashMessage.addSuccessMessage(redirectAttributes, "flash.entity.successful_deleted");

        return "redirect:/";
    }
}
