# -*- coding: utf-8 -*-
# Copyright (C) 2014 G&D Systems
# Developer: Dairon Medina Caro <info@gydsystems.com>
from openerp.osv import fields, osv
from lxml import etree
from openerp.osv.fields import _column
from datetime import datetime, timedelta, date

from openerp.tools.translate import _


class followup(osv.osv):
    """
    Model for Quotation follow Up
    """
    _name = 'quotation_followup.followup'
    _description = 'Quotation Follow-up'
    _rec_name = 'name'
    _columns = {
        'followup_line': fields.one2many('quotation_followup.followup.line', 'followup_id', 'Follow-up'),
        'company_id': fields.many2one('res.company', 'Company', required=True),
        'name': fields.related('company_id', 'name', string="Name"),
    }
    _defaults = {
        'company_id': lambda s, cr, uid, c: s.pool.get('res.company')._company_default_get(cr, uid,
                                                                                           'quotation_followup.followup',
                                                                                           context=c),
    }
    _sql_constraints = [('company_uniq', 'unique(company_id)', 'Only one follow-up per company is allowed')]


followup()


class followup_line(osv.osv):
    """
    Configuration of Follow Up
    """

    def _get_default_template(self, cr, uid, ids, context=None):
        """
        Gets the email template for sending
        """
        try:
            return self.pool.get('ir.model.data').get_object_reference(cr, uid, 'quotation_followup',
                                                                       'email_template_quotation_followup_default')[1]
        except ValueError:
            return False

    _name = 'quotation_followup.followup.line'
    _description = 'Follow-up Configuration'
    _columns = {
        'name': fields.char('Name', size=64, required=True, help="Ex: First reminder."),
        'sequence': fields.integer('Sequence', help="Gives the order when displaying list of follow-up lines."),
        'delay': fields.integer('Due Days',
                                help="The number of days after the quotation date to wait before sending "
                                     "the mail.",
                                required=True),
        'followup_id': fields.many2one('quotation_followup.followup', 'Follow Ups', required=True, ondelete="cascade"),
        'email_template_id': fields.many2one('email.template', 'Email Template', ondelete='set null'),
    }

    _order = 'delay'
    _sql_constraints = [('days_uniq', 'unique(followup_id, delay)', 'You must send mails on different days.')]
    _defaults = {
        'email_template_id': _get_default_template,
    }


followup_line()


class sale_order(osv.osv):
    """
    Override Sale Order
    """
    _inherit = 'sale.order'

    _columns = {
        'send_follow_mails': fields.boolean('Send Follow-up e-Mails'),
        'client_unsuscribed': fields.boolean('Client have stop getting reminders')
    }

    _default = {
        'send_follow_mails': True,
        'client_unsuscribed': False,
    }

    def send_followup(self, cr, uid, ids, context=None):
        """
        send email using the button
        """
        if context is None:
            context = {}
        quote = self.browse(self, cr, uid, context=context)

        template = self.pool.get('ir.model.data').get_object_reference(cr, uid, 'quotation_followup',
                                                                       'email_template_quotation_followup_default')[1]

        if self.pool.get('email.template').send_mail(cr, uid, template, ids,
                                                     force_send=True, context=context):
            raise osv.except_osv(_('Success!'), _('Email sent correctly to Customer.'))
        else:
            raise osv.except_osv(_('Error!'),
                                 _('Sorry.The was some error sending the message. Contact the Administrator.'))


    def maybe_send_followup(self, cr, uid, automatic=False, use_new_cursor=False, context=None):
        """
        Method executed by ir.cron
        to see if have to send the mail
        """
        if context is None:
            context = {}
        current_datetime = datetime.now()
        quotes_to_send_ids = self.search(cr, uid,
                                         [('send_follow_mails', '=', True), ('client_unsuscribed', '=', False)],
                                         context=context)

        followup_obj = self.pool.get('quotation_followup.followup')

        for quote in self.browse(cr, uid, quotes_to_send_ids, context=context):
            print "ID TO SEND: " + str(quote.id)

            quote_dt = quote.date_order

            followups_ids = followup_obj.search(cr, uid,
                                                [('company_id', '=', quote.company_id.id)],
                                                limit=1)
            for followups in followup_obj.browse(cr, uid, followups_ids, context=context):
                for followup_line in followups.followup_line:
                    delta = datetime.strptime(quote_dt, "%Y-%m-%d") + timedelta(
                        days=followup_line.delay)
                    print "THE DELTA IS: " + str(delta)
                    if delta.date() == current_datetime.date():
                        print "SEND TODAY! YEAH"
                        self.pool.get('email.template').send_mail(cr, uid, followup_line.email_template_id, quote.id,
                                                                  force_send=True, context=context)
                    else:
                        print "NOT SEND TODAY"

